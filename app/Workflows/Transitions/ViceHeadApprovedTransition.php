<?php

namespace App\Workflows\Transitions;

use App\Models\Dashboard;
use Workflow\Activity;
use Workflow\WorkflowStub;

class ViceHeadApprovedTransition extends Activity
{
    protected $dashboard;
    protected $workflow;

    public function execute(int $request): void
    {
        $this->loadDashboard($request);

        $this->workflow = WorkflowStub::load($this->dashboard->workflow_id);

        $this->workflow->vice_approve();

    }

    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
    }
}
