<?php

namespace App\Workflows;

use App\Models\Dashboard;
use App\Workflows\Notifications\NewRequestNotification;
use App\Workflows\Partials\RequestStates;
use App\Workflows\Transitions\ManagerApprovedTransition;
use Workflow\ActivityStub;
use Workflow\WorkflowStub;

class ResumeFromHeadTravelRequestWorkflow extends TravelRequestWorkflow
{
    public function execute(Dashboard $dashboard)
    {
        $dashboardId = $dashboard->id;

        yield ActivityStub::make(ManagerApprovedTransition::class, $dashboardId);
        yield $this->syncRequestState($dashboardId);

        yield ActivityStub::make(NewRequestNotification::class, RequestStates::UNIT_HEAD, $dashboardId);

        yield WorkflowStub::await(
            fn () => $this->HeadApproved() || $this->HeadDenied() || $this->HeadReturned()
        );

        if ($this->isFinalHeadDecision()) {
            yield from $this->syncStateAndNotify($dashboardId);

            return $this->getState();
        }

        yield $this->syncRequestState($dashboardId);
        yield ActivityStub::make(NewRequestNotification::class, RequestStates::FINANCIAL_OFFICER, $dashboardId);

        yield WorkflowStub::await(
            fn () => $this->FOApproved() || $this->FODenied() || $this->FOReturned()
        );

        yield from $this->syncStateAndNotify($dashboardId);

        return $this->getState();
    }
}
