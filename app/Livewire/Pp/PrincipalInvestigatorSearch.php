<?php

namespace App\Livewire\Pp;

use App\Services\Directory\SearchPresenters;
use App\Services\Proposal\PrincipalInvestigator;
use Livewire\Component;

class PrincipalInvestigatorSearch extends Component
{
    public string $search = '';
    public array $results = [];

    public function boot(): void
    {
        abort_unless(auth()->user()?->canAssignPrincipalInvestigator(), 403);
    }

    public function updatedSearch(SearchPresenters $directory): void
    {
        $this->results = mb_strlen(trim($this->search)) >= 2
            ? array_map(fn ($person) => (array) $person, $directory->execute($this->search))
            : [];
    }

    public function select(string $uid, PrincipalInvestigator $investigators): void
    {
        $profile = $investigators->directoryProfile($uid);
        $this->dispatch('principal-investigator-selected', ...$profile);
        $this->search = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.pp.principal-investigator-search');
    }
}
