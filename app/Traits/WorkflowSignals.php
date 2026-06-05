<?php

namespace App\Traits;

use App\Models\Dashboard;
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
use RuntimeException;
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
        if (isset($this->stateMachine)) {
            $id = $this->stateMachine->id ?? null;

            if ($id) {
                return Dashboard::query()->findOrFail($id);
            }

            return $this->stateMachine;
        }

        foreach ($this->arguments ?? [] as $argument) {
            if ($argument instanceof Dashboard) {
                return Dashboard::query()->findOrFail($argument->id);
            }
        }

        throw new RuntimeException('Unable to resolve dashboard for workflow signal.');
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
