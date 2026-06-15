<?php

namespace App\Livewire\Pp;

use App\Models\SettingsVice;
use App\Services\Directory\SearchPresenters;
use Livewire\Component;

class SentNotificationRecipients extends Component
{
    public array $recipients = [];

    public array $savedRecipients = [];

    public string $searchRecipient = '';

    public array $sukatUsers = [];

    public int $highlighted = 0;

    protected SearchPresenters $searchService;

    public function boot(SearchPresenters $search): void
    {
        $this->searchService = $search;
    }

    public function mount(): void
    {
        $settings = SettingsVice::firstOrCreate();

        $this->recipients = $settings->sent_notification_recipients ?? [];
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

    public function save(): void
    {
        $this->validate([
            'recipients' => ['array'],
            'recipients.*.name' => ['required', 'string'],
            'recipients.*.email' => ['required', 'email'],
        ]);

        $settings = SettingsVice::firstOrCreate();
        $settings->sent_notification_recipients = array_values($this->recipients);
        $settings->save();

        $this->savedRecipients = array_values($this->recipients);

        session()->flash('sent_notification_recipients_saved', 'Sent notification recipients updated.');
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
        return view('livewire.pp.sent-notification-recipients');
    }
}
