<?php

namespace App\Livewire\Pp;

use App\Models\ProjectProposal;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AllProjectProposalSearch extends Component
{
    use WithPagination;

    public string $searchProposal = "";
    public string $proposalStateFilter = "";
    public $review = false;
    public $resume = false;
    public bool $hideDenied = false;
    public bool $hideArchived = true;

    protected $listeners = [
        'pp-update' => '$refresh'
    ];

    public function updatedswitchDenied()
    {
        $this->hideDenied = !$this->hideDenied;
    }

    public function updatedSearchProposal(): void
    {
        $this->resetPage();
    }

    public function updatedProposalStateFilter(): void
    {
        $this->resetPage();
    }

    private function proposalStateGroups(): array
    {
        return [
            'awaiting' => ['submitted', 'complete'],
            'processing' => ['head_approved', 'fo_approved'],
            'returned' => ['head_returned', 'fo_returned', 'final_returned'],
            'approved' => ['final_approved', 'sent'],
            'granted' => ['granted'],
            'denied' => ['head_denied', 'fo_denied', 'final_denied', 'denied'],
        ];
    }

    public function fetchProposals()
    {
        $stateGroups = $this->proposalStateGroups();

        return ProjectProposal::with('dashboard')
            ->where('status_stage3', '!=', 'pending')
            ->where(function($query) {
                $query->where('name', 'like', '%'. $this->searchProposal .'%');

                if ($this->hideDenied) {
                    $query->orWhere('pp', 'like', '%'. $this->searchProposal .'%');
                } else {
                    $query->orWhereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(pp, '$.*')) LIKE ?",
                        ["%{$this->searchProposal}%"]
                    );
                }
            })
            ->when($this->hideDenied, function($query) {
                $query->where('pp->status', '!=', 'denied');
            })
            ->when(isset($stateGroups[$this->proposalStateFilter]), function($query) use ($stateGroups) {
                $query->whereHas('dashboard', function($query) use ($stateGroups) {
                    $query->whereIn('state', $stateGroups[$this->proposalStateFilter]);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        $user = Auth::user();
        $proposals = $this->fetchProposals();
        return view('livewire.pp.all-project-proposal-search',
            ['proposals' => $proposals]);
    }
}
