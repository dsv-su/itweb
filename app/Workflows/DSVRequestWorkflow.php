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
use Workflow\SignalMethod;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class DSVRequestWorkflow extends Workflow
{
    private $stateMachine;
    protected $checkRole;

    use WorkflowSignals;


    //Wait for statechange
    public function isSubmitted()
    {
        return $this->stateMachine->state->status() === 'submitted';
    }

    //Manager
    public function ManagerApproved()
    {
        return $this->stateMachine->state->status() === 'manager_approved';
    }

    public function ManagerReturned()
    {
        return $this->stateMachine->state->status() === 'manager_returned';
    }

    public function ManagerDenied()
    {
        return $this->stateMachine->state->status() === 'manager_denied';
    }

    //Head
    public function HeadApproved()
    {
        return $this->stateMachine->state->status() === 'head_approved';
    }

    public function HeadReturned()
    {
        return $this->stateMachine->state->status() === 'head_returned';
    }

    public function HeadDenied()
    {
        return $this->stateMachine->state->status() === 'head_denied';
    }

    //Finacial officer
    public function FOApproved()
    {
        return $this->stateMachine->state->status() === 'fo_approved';
    }

    public function FOReturned()
    {
        return $this->stateMachine->state->status() === 'fo_returned';
    }

    public function FODenied()
    {
        return $this->stateMachine->state->status() === 'fo_denied';
    }

    public function __construct(
        public StoredWorkflow $storedWorkflow, Dashboard $dashboard, ...$arguments)
    {
        parent::__construct($storedWorkflow, $dashboard, $arguments);
        $this->stateMachine = $dashboard;
        $this->checkRole = new CheckRoleforApprove();
    }

    public function execute(Dashboard $dashboard)
    {
        $userRequest = $dashboard->id;

        //Submitted by requester
        yield WorkflowStub::await(fn () => $this->isSubmitted());

        //Manager
        if($this->checkRole->isSameUserManager($userRequest)) {

            // User and Manager is same person
            $this->manager_approve();

            // Retrive new state
            $newState = $this->getState();
            $commonActivities = $this->getCommonActivities($userRequest);

            // Await stateupdate
            yield $commonActivities[0];
        } else {

            //Fire email to manager
            yield ActivityStub::make(NewRequestNotification::class, RequestStates::MANAGER, $userRequest);

            //Wait for manager to process request
            yield WorkflowStub::await(fn () => $this->ManagerApproved() || $this->ManagerDenied() || $this->ManagerReturned());

            // Handle managers decision
            $newState = $this->getState();

            // Activities
            $commonActivities = $this->getCommonActivities($userRequest);

            switch ($newState) {
                case RequestStates::MANAGER_APPROVED:
                    // Request has been approved by manager
                    yield $commonActivities[0];
                    break;
                case RequestStates::MANAGER_RETURNED:
                case RequestStates::MANAGER_DENIED:
                    // Request had been returned or denied by manager
                    foreach ($commonActivities as $activity) {
                        yield $activity;
                    }
                    //End workflow
                    return $this->stateMachine->state->status();
            }

        }

        //UnitHead
        if($this->checkRole->isSameManagerHead($userRequest)) {

            // Manager and Head is same person
            $this->head_approve();

            // Retrive new state
            $newState = $this->getState();
            $commonActivities = $this->getCommonActivities($userRequest);

            // Await stateupdate
            yield $commonActivities[0];

            // Notify FO
            yield ActivityStub::make(NewRequestNotification::class, RequestStates::FINACIAL_OFFICER, $userRequest);

        } else {

            // Notify Head
            yield ActivityStub::make(NewRequestNotification::class, RequestStates::UNIT_HEAD, $userRequest);

            //Wait for Head to process request
            yield WorkflowStub::await(fn () => $this->HeadApproved() || $this->HeadDenied() || $this->HeadReturned());

            //Handle Head decision
            $newState = $this->getState();
            $commonActivities = $this->getCommonActivities($userRequest);

            switch ($newState) {
                case RequestStates::HEAD_APPROVED:
                    //Request has been approved by head
                    yield $commonActivities[0];
                    //Notify FO
                    yield ActivityStub::make(NewRequestNotification::class, RequestStates::FINACIAL_OFFICER, $userRequest);
                    break;
                case RequestStates::HEAD_RETURNED:
                case RequestStates::HEAD_DENIED:
                    //Request has been returned or denied by head
                    foreach ($commonActivities as $activity) {
                        yield $activity;
                    }
                    //End workflow
                    return $this->stateMachine->state->status();
            }

        }

        //FO
        yield WorkflowStub::await(fn () => $this->FOApproved() || $this->FODenied() || $this->FOReturned());

        //Handle FO decision
        $newState = $this->getState();
        $commonActivities = $this->getCommonActivities($userRequest);

        switch ($newState) {
            case RequestStates::FO_APPROVED:
                //Request has been approved by fo
                foreach ($commonActivities as $activity) {
                    yield $activity;
                }
                break;
            case RequestStates::FO_RETURNED:
            case RequestStates::FO_DENIED:
                //Request has been returned or denied by fo
                foreach ($commonActivities as $activity) {
                    yield $activity;
                }
                //End workflow
                return $this->stateMachine->state->status();
        }

        //End workflow
        return $this->stateMachine->state->status();
    }

    protected function getState()
    {
        return $this->stateMachine->state->status();
    }

    protected function getCommonActivities($userRequest)
    {
        return [
            ActivityStub::make(StateUpdateTransition::class, $userRequest),
            ActivityStub::make(StateUpdateNotification::class, $userRequest),
        ];
    }
}
