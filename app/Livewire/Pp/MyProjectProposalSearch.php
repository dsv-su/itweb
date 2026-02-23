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

    protected $listeners = [
        'pp-update' => '$refresh'
    ];

    public function render()
    {
        $user = Auth::user();
        $search = $this->searchProposal;
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
            ->orderBy('created_at', 'desc')
            ->paginate(5);



        return view('livewire.pp.my-project-proposal-search',
        ['proposals' => $proposals]);
    }
}
