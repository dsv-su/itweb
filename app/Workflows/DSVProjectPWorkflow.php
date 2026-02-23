<?php

namespace App\Workflows;

use App\Models\Dashboard;
use App\Traits\ProjectProSignals;
use App\Workflows\Checks\CheckFilesUploaded;
use App\Workflows\Checks\CheckUploadedFiles;
use App\Workflows\Notifications\NewFinalApprovalNotification;
use App\Workflows\Notifications\NewPPtoFinance;
use App\Workflows\Notifications\NewProjectProposalNotification;
use App\Workflows\Notifications\StateUpdateNotification;
use App\Workflows\Partials\RequestStates;
use App\Workflows\Transitions\Stage2UpdateTransition;
use App\Workflows\Transitions\StateUpdateTransition;
use Workflow\ActivityStub;
use Workflow\Models\StoredWorkflow;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class DSVProjectPWorkflow extends Workflow
{
    private $stateMachine;
    protected $heads;

    use ProjectProSignals;

    //Submit
    public function isSubmitted()
    {
        return $this->stateMachine->state->status() === 'submitted';
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

    //Complete
    public function isComplete()
    {
        return $this->stateMachine->state->status() === 'complete';
    }

    //Vice
    public function ViceApproved()
    {
        return $this->stateMachine->state->status() === 'vice_approved';
    }

    public function ViceReturned()
    {
        return $this->stateMachine->state->status() === 'vice_returned';
    }

    public function ViceDenied()
    {
        return $this->stateMachine->state->status() === 'vice_denied';
    }

    //Uploaded files
    public function UploadedFiles()
    {
        return $this->files_uploaded;
    }

    //Changed files
    public function DraftFilesChanged()
    {
        return $this->files_draft_changed;
    }

    public function BudgetFilesChanged()
    {
        return $this->files_budget_changed;
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

    //Final approval
    public function FinalApproved()
    {
        return $this->stateMachine->state->status() === 'final_approved';
    }

    public function FinalReturned()
    {
        return $this->stateMachine->state->status() === 'final_returned';
    }

    public function FinalDenied()
    {
        return $this->stateMachine->state->status() === 'final_denied';
    }

    public function __construct(
        public StoredWorkflow $storedWorkflow, Dashboard $dashboard, ...$arguments)
    {
        parent::__construct($storedWorkflow, $dashboard, $arguments);
        $this->stateMachine = $dashboard;
    }

    public function execute(Dashboard $dashboard)
    {
        //(1)Use dashbordID
        $userRequest = $dashboard->id;

        //Wait for submit signal
        yield WorkflowStub::await(fn () => $this->isSubmitted());

        //Update dashboardstate
        $commonActivities = $this->getCommonActivities($userRequest);
        yield $commonActivities[0];

        //Check for uploaded files - send user reminder
        yield ActivityStub::make(CheckUploadedFiles::class, $userRequest);

        //(2)Wait for complete signal
        yield WorkflowStub::await(fn () => ($this->isComplete()));

        //Notify Head
        yield ActivityStub::make(NewProjectProposalNotification::class, RequestStates::UNIT_HEAD, $userRequest);
        //yield ActivityStub::make(PPStatusUpdateUsersStage1::class, RequestStates::UNIT_HEAD, 'review', $userRequest);

        //(3)Wait for head decision signal
        yield WorkflowStub::await(fn () => ($this->HeadApproved() || $this->HeadDenied() || $this->HeadReturned()));

        //Update dashboardstate
        $newState = $this->getState();
        $commonActivities = $this->getCommonActivities($userRequest);
        yield $commonActivities[0];

        //Handle Head reject decision
        switch ($newState) {
            case RequestStates::HEAD_RETURNED:
            case RequestStates::HEAD_DENIED:
                //Request has been returned or denied by head
                foreach ($commonActivities as $activity) {
                    yield $activity;
                }
                //End workflow
                return $this->stateMachine->state->status();
        }

        //Notify FO (for review)
        //yield ActivityStub::make(NewProjectProposalNotification::class, RequestStates::FINACIAL_OFFICER, $userRequest);
        //Notify entire FO group
        yield ActivityStub::make(NewPPtoFinance::class, $userRequest);

        //(4)Wait for FO decision
        yield WorkflowStub::await(fn () => ($this->FOApproved() || $this->FODenied() || $this->FOReturned()));

        //Update dashboardstate
        $newState = $this->getState();
        $commonActivities = $this->getCommonActivities($userRequest);
        yield $commonActivities[0];

        //Handle FO decision
        switch ($newState) {
            case RequestStates::FO_RETURNED:
            case RequestStates::FO_DENIED:
                //Request has been returned or denied by FO
                foreach ($commonActivities as $activity) {
                    yield $activity;
                }
                //End workflow
                return $this->stateMachine->state->status();
        }

        //Request has been approved by fo
        //Update stage2
        yield ActivityStub::make(Stage2UpdateTransition::class, $userRequest);

        //Final approval request Email to Vice
        yield ActivityStub::make(NewFinalApprovalNotification::class, RequestStates::VICE, $userRequest);

        //(5)Wait for Final decision
        yield WorkflowStub::await(fn () => ($this->FinalApproved() || $this->FinalDenied() || $this->FinalReturned()));

        //Notify user
        $commonActivities = $this->getCommonActivities($userRequest);
        foreach ($commonActivities as $activity) {
            yield $activity;
        }

        //Update stage2
        yield ActivityStub::make(Stage2UpdateTransition::class, $userRequest);

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

    protected function getHeads($userRequest)
    {
        return ActivityStub::make(HeadsStatus::class, $userRequest);
    }
}
