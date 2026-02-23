<?php

namespace App\Livewire\Pp;

use App\Models\Dashboard;
use App\Models\FundingOrganization;
use App\Models\ProjectProposal;
use App\Services\Awaiting\AwaitingDashboard;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ProjectProposalHome extends Component
{
    public $proposals;
    public $myproposals;
    public $awaiting;
    public $sent;
    public int $myCount;
    public int $allCount;

    public function mount()
    {
        $this->proposals = ProjectProposal::where('status_stage3', '!=', 'pending')->get();
        $user = Auth::user();
        $this->my($user);
        $this->awaiting($user);
        $this->sentproposals();
        $this->myCount = $this->myproposals->count();
        $this->allCount = $this->proposals->count();
    }

    public function hydrate()
    {
        $user = Auth::user();
        $this->awaiting($user);
    }

    public function my($user)
    {
        $this->myproposals = ProjectProposal::where('user_id', $user->id)
            ->where('status_stage3', '!=', 'pending')->get();
    }

    public function awaiting($user)
    {
        $awiting = new AwaitingDashboard($user);
        $this->awaiting =  $awiting->proposals()->count();
    }

    public function allproposals()
    {
        $this->dispatch('allproposals');
    }

    public function sentproposals()
    {
        //Dashboard sent states
        $sent_states = [
            'sent', 'granted'
        ];

        //Fetch and count sent proposals
        $this->sent = Dashboard::whereIn('state', $sent_states)->count();
    }

    public function render()
    {
        return view('livewire.pp.project-proposal-home');
    }
}
