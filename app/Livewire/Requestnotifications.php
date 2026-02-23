<?php

namespace App\Livewire;

use App\Models\Dashboard;
use App\Traits\DashboardRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Statamic\Auth\User;

class Requestnotifications extends Component
{
    use DashboardRequests;

    public $auth_user;
    public $user_roles;
    public $requests;

    public function mount()
    {
        $this->user_roles = $this->getUserRoles();
        $this->getRequests();
    }

    public function getRequests()
    {
        $this->requests = $this->Dashboardtask($this->auth_user);
    }

    public function hydrate()
    {
        $this->getRequests();
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
        return view('livewire.requestnotifications');
    }
}
