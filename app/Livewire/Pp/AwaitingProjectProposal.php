<?php

namespace App\Livewire\Pp;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Services\Awaiting\AwaitingDashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AwaitingProjectProposal extends Component
{
    public $review = false;
    public $resume = false;

    public function render()
    {
        //Awaiting proposals
        $user = Auth::user();
        $awiting = new AwaitingDashboard($user);
        $awaitingDashboard = $awiting->proposals();

        $proposals = ProjectProposal::with('dashboard')
            ->whereIn('id', $awaitingDashboard)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->review = true;

        return view('livewire.pp.awaiting-project-proposal',
        ['proposals' => $proposals]);
    }
}
