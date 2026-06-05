<?php

namespace App\Workflows;

use App\Models\Dashboard;
use App\Traits\WorkflowSignals;
use App\Workflows\Notifications\NewRequestNotification;
use App\Workflows\Notifications\StateUpdateNotification;
use App\Workflows\Partials\CheckRoleforApprove;
use App\Workflows\Partials\RequestStates;
use App\Workflows\Transitions\StateUpdateTransition;
use Workflow\ActivityStub;
use Workflow\Models\StoredWorkflow;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class TravelRequestWorkflow extends Workflow
{
    use WorkflowSignals;

    private Dashboard $stateMachine;

    private int $dashboardId;

    protected CheckRoleforApprove $checkRole;

    public function __construct(
        public StoredWorkflow $storedWorkflow,
        Dashboard $dashboard,
        ...$arguments
    ) {
        parent::__construct($storedWorkflow, $dashboard, $arguments);

        $this->stateMachine = $dashboard;
        $this->dashboardId = $dashboard->id;
        $this->checkRole = new CheckRoleforApprove;
    }

    public function isSubmitted(): bool
    {
        return $this->isState(RequestStates::SUBMITTED);
    }

    public function ManagerApproved(): bool
    {
        return $this->isState(RequestStates::MANAGER_APPROVED);
    }

    public function ManagerReturned(): bool
    {
        return $this->isState(RequestStates::MANAGER_RETURNED);
    }

    public function ManagerDenied(): bool
    {
        return $this->isState(RequestStates::MANAGER_DENIED);
    }

    public function HeadApproved(): bool
    {
        return $this->isState(RequestStates::HEAD_APPROVED);
    }

    public function HeadReturned(): bool
    {
        return $this->isState(RequestStates::HEAD_RETURNED);
    }

    public function HeadDenied(): bool
    {
        return $this->isState(RequestStates::HEAD_DENIED);
    }

    public function FOApproved(): bool
    {
        return $this->isState(RequestStates::FO_APPROVED);
    }

    public function FOReturned(): bool
    {
        return $this->isState(RequestStates::FO_RETURNED);
    }

    public function FODenied(): bool
    {
        return $this->isState(RequestStates::FO_DENIED);
    }

    public function execute(Dashboard $dashboard)
    {
        $dashboardId = $dashboard->id;
        $this->dashboardId = $dashboardId;
        $this->checkRole ??= new CheckRoleforApprove;

        if (! $this->checkRole->isSameUserManager($dashboardId)) {
            yield ActivityStub::make(NewRequestNotification::class, RequestStates::MANAGER, $dashboardId);

            yield WorkflowStub::await(
                fn () => $this->ManagerApproved() || $this->ManagerDenied() || $this->ManagerReturned()
            );

            if ($this->isFinalManagerDecision()) {
                yield from $this->syncStateAndNotify($dashboardId);

                return $this->getState();
            }

            yield $this->syncRequestState($dashboardId);
        }

        if ($this->checkRole->isSameManagerHead($dashboardId)) {
            yield ActivityStub::make(NewRequestNotification::class, RequestStates::FINANCIAL_OFFICER, $dashboardId);
        } else {
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
        }

        yield WorkflowStub::await(
            fn () => $this->FOApproved() || $this->FODenied() || $this->FOReturned()
        );

        yield from $this->syncStateAndNotify($dashboardId);

        return $this->getState();
    }

    protected function getState(): ?string
    {
        $dashboardId = $this->resolveDashboardId();

        if (! $dashboardId) {
            return null;
        }

        $fresh = Dashboard::query()->find($dashboardId);

        if (! $fresh) {
            return null;
        }

        return is_object($fresh->state) ? $fresh->state->status() : (string) $fresh->state;
    }

    private function resolveDashboardId(): ?int
    {
        if (isset($this->dashboardId)) {
            return $this->dashboardId;
        }

        if (isset($this->stateMachine) && $this->stateMachine->id) {
            $this->dashboardId = $this->stateMachine->id;

            return $this->dashboardId;
        }

        foreach ($this->arguments ?? [] as $argument) {
            if ($argument instanceof Dashboard) {
                $this->dashboardId = $argument->id;

                return $this->dashboardId;
            }
        }

        return null;
    }

    protected function getCommonActivities(int $dashboardId): array
    {
        return [
            ActivityStub::make(StateUpdateTransition::class, $dashboardId),
            ActivityStub::make(StateUpdateNotification::class, $dashboardId),
        ];
    }

    private function isState(string $state): bool
    {
        return $this->getState() === $state;
    }

    protected function isFinalManagerDecision(): bool
    {
        return in_array($this->getState(), [
            RequestStates::MANAGER_RETURNED,
            RequestStates::MANAGER_DENIED,
        ], true);
    }

    protected function isFinalHeadDecision(): bool
    {
        return in_array($this->getState(), [
            RequestStates::HEAD_RETURNED,
            RequestStates::HEAD_DENIED,
        ], true);
    }

    protected function syncRequestState(int $dashboardId)
    {
        return ActivityStub::make(StateUpdateTransition::class, $dashboardId);
    }

    protected function syncStateAndNotify(int $dashboardId): iterable
    {
        foreach ($this->getCommonActivities($dashboardId) as $activity) {
            yield $activity;
        }
    }
}
