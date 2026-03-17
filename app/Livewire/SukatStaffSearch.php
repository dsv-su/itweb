<?php

namespace App\Livewire;

use App\Services\Ldap\SukatUser;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class SukatStaffSearch extends Component
{
    /*** Disabled 2026-03-17 **/

    public string $query = '';
    public array $sukatusers = [];
    public int $highlightIndex = 0;

    // Person is an array because it hydrates cleanly; allow null if nothing found.
    public ?array $person = null;

    public function mount(): void
    {
        $this->resetData();
        $this->defaultUser();
    }

    public function resetData(): void
    {
        $this->query = '';
        $this->sukatusers = [];
        $this->highlightIndex = 0;
    }

    public function incrementHighlight(): void
    {
        $count = count($this->sukatusers);
        if ($count === 0) return;

        $this->highlightIndex = ($this->highlightIndex === $count - 1)
            ? 0
            : $this->highlightIndex + 1;
    }

    public function decrementHighlight(): void
    {
        $count = count($this->sukatusers);
        if ($count === 0) return;

        $this->highlightIndex = ($this->highlightIndex === 0)
            ? $count - 1
            : $this->highlightIndex - 1;
    }

    public function defaultUser(): void
    {
        $user = null;

        if (App::isLocal()) {
            $user = SukatUser::where('uid', 'gwett')->first();
        } else {
            $email = auth()->user()?->email;
            if ($email) {
                $user = SukatUser::where('mail', $email)->first();
            }
        }

        $this->person = $user?->toArray(); // null-safe
    }

    public function selectUser(int $index = -1): void
    {
        // Prefer passing an index from the UI; default to the highlighted item.
        $selected = $index >= 0
            ? ($this->sukatusers[$index] ?? null)
            : ($this->sukatusers[$this->highlightIndex] ?? null);

        if ($selected) {
            $this->person = $selected;
        }

        $this->resetData();
    }

    public function updatedQuery(): void
    {
        $q = trim($this->query);

        // Avoid “empty string” searches and noisy 1-char searches.
        if ($q === '' || mb_strlen($q) < 2) {
            $this->sukatusers = [];
            $this->highlightIndex = 0;
            return;
        }

        $this->sukatusers = SukatUser::where('cn', 'starts_with', $q)
            ->limit(5)
            ->get()
            ->toArray();

        $this->highlightIndex = 0;
    }

    public function render()
    {
        return view('livewire.sukat-staff-search');
    }
}
