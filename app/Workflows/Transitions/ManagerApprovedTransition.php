<?php

namespace App\Workflows\Transitions;

use App\Models\Dashboard;
use Workflow\Activity;
use Workflow\WorkflowStub;

class ManagerApprovedTransition extends Activity
{
    protected Dashboard $dashboard;

    protected $workflow;

    public function execute(int $dashboardId): void
    {
        $this->dashboard = Dashboard::findOrFail($dashboardId);
        $this->workflow = WorkflowStub::load($this->dashboard->workflow_id);

        $this->workflow->manager_approve();
    }
}
