<?php

namespace App\Workflows\Transitions;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use Workflow\Activity;

class Stage2UpdateTransition extends Activity
{
    protected $dashboard, $proposal;

    public function execute($request)
    {
        //Retrive dashboard
        $id = $request;
        $this->dashboard = Dashboard::find($id);

        //Update Proposal stage
        $this->proposal = ProjectProposal::find($this->dashboard->request_id);
        $this->proposal->status_stage2 = $this->dashboard->state;
        $this->proposal->save();

    }
}
