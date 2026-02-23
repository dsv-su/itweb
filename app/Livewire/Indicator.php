<?php

namespace App\Livewire;

use App\Models\Dashboard;
use App\Traits\DashboardIndicator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Statamic\Auth\User;

class Indicator extends Component
{
    use DashboardIndicator;

    public $auth_user;
    public $user_roles;
    public $dashboard;
    public $status;

    public function mount()
    {
        $this->dashboard = null;
        //$this->status = 'Rt';
        //$this->user_roles = $this->getUserRoles();
        $this->auth_user = Auth::user()->id;
        $this->checkDashboard();
    }

    public function checkDashboard()
    {
        //Check dashboard tasks
        $this->dashboard = $this->DashboardIndicator($this->auth_user);
    }

    public function hydrate()
    {
        $this->checkDashboard();
    }

    private function getUserRoles()
    {
        $this->auth_user = Auth::user()->id;
        $user = User::find($this->auth_user);
        $userrole = collect($user->roles());
        return $userrole->keys()->all();
    }

    public function render()
    {
        return view('livewire.indicator');
    }
}
