<?php

namespace App\Livewire\Pp\Fo;

use App\Mail\NotifyAssignedFO;
use App\Mail\NotifyFONewProjectProposal;
use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Workflows\Partials\RequestStates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Assign extends Component
{
    public ProjectProposal $proposal;
    public $fos;
    public $fo_user_id;
    public $finaceOfficers;
    public $dashboard;

    public function mount(ProjectProposal $proposal)
    {
        $this->proposal = $proposal;
        $this->fo_user_id = $proposal->foUser->id;
        $this->getFO();
        $this->loadDashboard($proposal->dashboard->id);
    }

    public function updatedFoUserId($value)
    {
        Dashboard::where('request_id', $this->proposal->id)->update(['fo_id' => $value]);
        $assignedFO = User::find($value);
        //Send email only if proposal is in review state
        if((string)$this->dashboard->state === RequestStates::HEAD_APPROVED){
            Mail::to($assignedFO->email)->send(
                new NotifyAssignedFO($assignedFO, $this->dashboard)
            );
        }
    }

    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
    }

    public function getFO()
    {
        $ids = DB::table('group_user')->where('group_id', 'ekonomi')->pluck('user_id');
        $this->fos = User::whereIn('id', $ids)->get();
        $this->finaceOfficers = $this->fos->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.pp.fo.assign');
    }
}

