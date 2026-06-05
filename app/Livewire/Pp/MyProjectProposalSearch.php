<?php

namespace App\Livewire\Pp;

use App\Models\ProjectProposal;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyProjectProposalSearch extends Component
{
    use WithPagination;

    public string $searchProposal = "";
    public string $proposalStateFilter = "";

    protected $listeners = [
        'pp-update' => '$refresh'
    ];

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

    public function render()
    {
        $user = Auth::user();
        $search = $this->searchProposal;
        $stateGroups = $this->proposalStateGroups();
        $proposals = ProjectProposal::with('dashboard')
            //Filter to the authenticated user
            ->where('user_id', $user->id)
            //Exclude pending
            ->where('status_stage3', '!=', 'pending')
            //Wrap ALL search conditions together
            ->when($search, function($q) use ($search) {
                $q->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('id',   'like', "%{$search}%")
                        ->orWhereRaw(
                            "JSON_UNQUOTE(JSON_EXTRACT(pp, '$.*')) LIKE ?",
                            ["%{$search}%"]
                        )
                        ->orWhereHas('dashboard', function($q2) use ($search) {
                            $q2->where('state', 'like', "%{$search}%");
                    });
                });
            })
            ->when(isset($stateGroups[$this->proposalStateFilter]), function($q) use ($stateGroups) {
                $q->whereHas('dashboard', function($q2) use ($stateGroups) {
                    $q2->whereIn('state', $stateGroups[$this->proposalStateFilter]);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);



        return view('livewire.pp.my-project-proposal-search',
        ['proposals' => $proposals]);
    }
}
