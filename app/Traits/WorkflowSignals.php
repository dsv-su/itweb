<?php

namespace App\Traits;

use App\Workflows\States\FOApproved;
use App\Workflows\States\FODenied;
use App\Workflows\States\FOReturned;
use App\Workflows\States\HeadApproved;
use App\Workflows\States\HeadDenied;
use App\Workflows\States\HeadReturned;
use App\Workflows\States\ManagerApproved;
use App\Workflows\States\ManagerDenied;
use App\Workflows\States\ManagerReturned;
use App\Workflows\States\Submitted;
use Workflow\SignalMethod;

trait WorkflowSignals
{
    #[SignalMethod]
    public function submit()
    {
        $this->stateMachine->state->transitionTo(Submitted::class);
    }

    #[SignalMethod]
    public function manager_approve()
    {
        $this->stateMachine->state->transitionTo(ManagerApproved::class);
    }

    #[SignalMethod]
    public function manager_return()
    {
        $this->stateMachine->state->transitionTo(ManagerReturned::class);
    }

    #[SignalMethod]
    public function manager_deny()
    {
        $this->stateMachine->state->transitionTo(ManagerDenied::class);
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
}
