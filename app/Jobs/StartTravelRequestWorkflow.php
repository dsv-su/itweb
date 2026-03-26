<?php

namespace App\Jobs;

use App\Models\Dashboard;
use App\Workflows\DSVRequestWorkflow;
use App\Workflows\Partials\CheckRoleforApprove;
use App\Workflows\TravelRequestWorkflow;
use App\Workflows\UserManagerTravelRequestWorkflow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Workflow\WorkflowStub;

class StartTravelRequestWorkflow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public function __construct(
        public int $dashboardId,
        public int $attemptNumber = 1
    ) {
    }

    public function handle(): void
    {
        $dashboard = Dashboard::find($this->dashboardId);

        if (! $dashboard) {
            return;
        }

        $checkRole = new CheckRoleforApprove();
        $shouldAutoManagerApprove = $checkRole->isSameUserManager($dashboard->id);
        $shouldAutoHeadApprove = $checkRole->isSameManagerHead($dashboard->id);

        // Start the workflow (idempotency guard)
        if (! $dashboard->workflow_id) {
            $workflow = WorkflowStub::make(TravelRequestWorkflow::class);
            $dashboard->workflow_id = $workflow->id();
            $dashboard->save();
            $workflow->start($dashboard);
            $workflow->submit();
        } else {
            $workflow = WorkflowStub::load($dashboard->workflow_id);
        }
        // Check if User is Manager and Head
        if ($this->attemptNumber <= 3) {
            if ($shouldAutoManagerApprove) { $workflow->manager_approve(); }
            if ($shouldAutoHeadApprove) { $workflow->head_approve(); }
        }
    }
}
