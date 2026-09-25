<?php

namespace App\Livewire\Pp;

use App\Mail\MonthlyProposalStatistics;
use App\Models\SettingsVice;
use App\Services\Directory\SearchPresenters;
use App\Services\Stats\MonthlyProposalStats;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Throwable;

class MonthlyStatsRecipients extends Component
{
    public array $recipients = [];

    public array $savedRecipients = [];

    public string $searchRecipient = '';

    public array $sukatUsers = [];

    public int $highlighted = 0;

    protected SearchPresenters $searchService;

    public function boot(SearchPresenters $search): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->isVice() || $user->isSuperAdmin()), 403);

        $this->searchService = $search;
    }

    public function mount(): void
    {
        $settings = SettingsVice::firstOrCreate();

        $this->recipients = $settings->monthly_stats_recipients ?? [];
        $this->savedRecipients = $this->recipients;
    }

    public function moveHighlight(int $direction): void
    {
        $count = count($this->sukatUsers);
        if ($count === 0) {
            return;
        }

        $this->highlighted = ($this->highlighted + $direction + $count) % $count;
    }

    public function addHighlighted(): void
    {
        if (isset($this->sukatUsers[$this->highlighted])) {
            $user = $this->sukatUsers[$this->highlighted];
            $this->addRecipient($user->uid, $user->name, $user->email, $user->role);
        }
    }

    public function updatedSearchRecipient(): void
    {
        $this->sukatUsers = $this->searchService->execute($this->searchRecipient);
        $this->highlighted = 0;
    }

    public function addRecipient($uid, $name, $email, $role = null): void
    {
        $uid = $uid ?: null;
        $name = trim((string) $name);
        $email = trim((string) $email);

        if ($name === '' || $email === '') {
            return;
        }

        $exists = collect($this->recipients)->contains(function (array $recipient) use ($uid, $email) {
            if ($uid && ! empty($recipient['uid'])) {
                return (string) $recipient['uid'] === (string) $uid;
            }

            return strcasecmp($recipient['email'] ?? '', $email) === 0;
        });

        if ($exists) {
            $this->searchRecipient = '';
            $this->sukatUsers = [];

            return;
        }

        $this->recipients[] = [
            'uid' => $uid,
            'name' => $name,
            'email' => $email,
            'type' => 'sukat',
            'role' => $role,
        ];

        $this->searchRecipient = '';
        $this->sukatUsers = [];
    }

    public function removeRecipient(int $index): void
    {
        array_splice($this->recipients, $index, 1);
    }

    public function sendNow(string $email, MonthlyProposalStats $statistics): void
    {
        $this->resetErrorBag('monthly_stats_send');
        session()->forget('monthly_stats_sent');

        $recipient = collect(SettingsVice::first()?->monthly_stats_recipients ?? [])
            ->first(fn ($recipient) => is_array($recipient) && ($recipient['email'] ?? '') === $email);

        if (! $recipient || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('monthly_stats_send', 'Save a valid recipient before sending monthly statistics.');

            return;
        }

        try {
            $month = CarbonImmutable::now('Europe/Stockholm')->startOfMonth()->subMonth();
            $stats = $statistics->build($month);
            Mail::to($recipient['email'])->send(new MonthlyProposalStatistics($stats, $recipient));
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('monthly_stats_send', 'Monthly statistics could not be sent. Please try again.');

            return;
        }

        session()->flash('monthly_stats_sent', 'Monthly statistics for '.$stats['month'].' sent to '.$recipient['email'].'.');
    }

    public function save(): void
    {
        $this->validate([
            'recipients' => ['array'],
            'recipients.*.uid' => ['required', 'string'],
            'recipients.*.name' => ['required', 'string'],
            'recipients.*.email' => ['required', 'email'],
        ]);

        $settings = SettingsVice::firstOrCreate();
        $settings->monthly_stats_recipients = array_values($this->recipients);
        $settings->save();

        $this->savedRecipients = array_values($this->recipients);

        session()->flash('monthly_stats_recipients_saved', 'Monthly statistics recipients updated.');
    }

    public function hasUnsavedChanges(): bool
    {
        return $this->recipientKeys($this->recipients) !== $this->recipientKeys($this->savedRecipients);
    }

    public function recipientIsStored(array $recipient): bool
    {
        return in_array($this->recipientKey($recipient), $this->recipientKeys($this->savedRecipients), true);
    }

    private function recipientKeys(array $recipients): array
    {
        $keys = array_map(fn (array $recipient): string => $this->recipientKey($recipient), $recipients);
        sort($keys);

        return $keys;
    }

    private function recipientKey(array $recipient): string
    {
        if (! empty($recipient['uid'])) {
            return 'uid:'.strtolower((string) $recipient['uid']);
        }

        return 'email:'.strtolower((string) ($recipient['email'] ?? ''));
    }

    public function render()
    {
        return view('livewire.pp.monthly-stats-recipients');
    }
}
