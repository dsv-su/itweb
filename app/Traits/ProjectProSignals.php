<?php

namespace App\Traits;

use App\Workflows\States\Complete;
use App\Workflows\States\FinalApproved;
use App\Workflows\States\FinalDenied;
use App\Workflows\States\FinalReturned;
use App\Workflows\States\FOApproved;
use App\Workflows\States\FODenied;
use App\Workflows\States\FOReturned;
use App\Workflows\States\HeadApproved;
use App\Workflows\States\HeadDenied;
use App\Workflows\States\HeadReturned;
use App\Workflows\States\Submitted;
use App\Workflows\States\ViceApproved;
use App\Workflows\States\ViceDenied;
use App\Workflows\States\ViceReturned;
use Workflow\SignalMethod;

trait ProjectProSignals
{
    protected $files_uploaded = false;
    protected  $files_draft_changed = false;
    protected  $files_budget_changed = false;

    #[SignalMethod]
    public function submit()
    {
        $this->stateMachine->state->transitionTo(Submitted::class);
    }

    #[SignalMethod]
    public function head_approve()
    {
        $this->stateMachine->state->transitionTo(HeadApproved::class);
    }

    #[SignalMethod]
    public function head_return()
    {
        $this->stateMachine->state->transitionTo(HeadReturned::class);
    }

    #[SignalMethod]
    public function head_deny()
    {
        $this->stateMachine->state->transitionTo(HeadDenied::class);
    }

    #[SignalMethod]
    public function vice_approve()
    {
        $this->stateMachine->state->transitionTo(ViceApproved::class);
    }

    #[SignalMethod]
    public function vice_return()
    {
        $this->stateMachine->state->transitionTo(ViceReturned::class);
    }

    #[SignalMethod]
    public function vice_deny()
    {
        $this->stateMachine->state->transitionTo(ViceDenied::class);
    }

    #[SignalMethod]
    public function complete()
    {
        $this->stateMachine->state->transitionTo(Complete::class);
    }

    #[SignalMethod]
    public function setfilesUploaded($status)
    {
        $this->files_uploaded = $status;
    }

    #[SignalMethod]
    public function setDraftFilesChanged($status)
    {
        $this->files_draft_changed = $status;
    }

    #[SignalMethod]
    public function setBudgetFilesChanged($status)
    {
        $this->files_budget_changed = $status;
    }

    #[SignalMethod]
    public function fo_approve()
    {
        $this->stateMachine->state->transitionTo(FOApproved::class);
    }

    #[SignalMethod]
    public function fo_return()
    {
        $this->stateMachine->state->transitionTo(FOReturned::class);
    }

    #[SignalMethod]
    public function fo_deny()
    {
        $this->stateMachine->state->transitionTo(FODenied::class);
    }

    #[SignalMethod]
    public function final_approve()
    {
        $this->stateMachine->state->transitionTo(FinalApproved::class);
    }

    #[SignalMethod]
    public function final_return()
    {
        $this->stateMachine->state->transitionTo(FinalReturned::class);
    }

    #[SignalMethod]
    public function final_deny()
    {
        $this->stateMachine->state->transitionTo(FinalDenied::class);
    }
}
