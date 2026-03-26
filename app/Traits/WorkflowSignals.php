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
use App\Models\Dashboard;
use Workflow\SignalMethod;

trait WorkflowSignals
{
    #[SignalMethod]
    public function submit()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(Submitted::class);
    }

    private function freshDashboard(): Dashboard
    {
        $id = $this->stateMachine->id ?? null;

        if (! $id) {
            return $this->stateMachine;
        }

        return Dashboard::query()->findOrFail($id);
    }
    #[SignalMethod]
    public function manager_approve()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(ManagerApproved::class);
    }

    #[SignalMethod]
    public function manager_return()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(ManagerReturned::class);
    }

    #[SignalMethod]
    public function manager_deny()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(ManagerDenied::class);
    }

    #[SignalMethod]
    public function head_approve()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(HeadApproved::class);
    }

    #[SignalMethod]
    public function head_return()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(HeadReturned::class);
    }

    #[SignalMethod]
    public function head_deny()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(HeadDenied::class);
    }

    #[SignalMethod]
    public function fo_approve()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(FOApproved::class);
    }

    #[SignalMethod]
    public function fo_return()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(FOReturned::class);
    }

    #[SignalMethod]
    public function fo_deny()
    {
        $dashboard = $this->freshDashboard();
        $dashboard->state->transitionTo(FODenied::class);
    }
}
