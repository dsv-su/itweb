<?php

namespace App\Livewire;

use App\Services\Ldap\SukatUser;
use Illuminate\Support\Facades\App;
use Livewire\Component;

/*class SukatStaffSearch extends Component
{
    public $query;
    public $sukatusers;
    public $highlightIndex;
    public $person;

    public function mount()
    {
        $this->resetData();
        $this->defaultUser();
    }

    public function resetData()
    {
        $this->query = '';
        $this->sukatusers = [];
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->sukatusers) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->sukatusers) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function defaultUser()
    {
        if(App::isLocal()) {
            $this->person = SukatUser::where('uid', '=', 'gwett')->first()->toArray();
        } else {
            $this->person = SukatUser::where('mail', '=', auth()->user()->email)->first()->toArray();
        }

    }

    public function selectUser($id=0)
    {
        if($id === 0) {
            $selecteduser = $this->sukatusers[$this->highlightIndex] ?? null;
        } else {
            $selecteduser = $this->sukatusers[$id] ?? null;
        }

        if ($selecteduser) {
            $this->person = $selecteduser;
        }
        $this->resetData();
        //dd($this->person);
    }


    public function updatedQuery()

    {
        $this->sukatusers = SukatUser::where('cn', 'starts_with', $this->query)->limit(5)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.sukat-staff-search');
    }
}*/
class SukatStaffSearch extends Component
{
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
