<?php

namespace App\Livewire;

use App\Traits\DashboardRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Statamic\Auth\User;

class Notificationstoggler extends Component
{
    use DashboardRequests;

    public $auth_user;
    public $user_roles;
    public $requests;
    public $returned;

    public function mount()
    {
        $this->user_roles = $this->getUserRoles();
        $this->requests = $this->Dashboardtask($this->auth_user);
        $this->returned = $this->returnedDashboardtask($this->auth_user);

        if($this->returned == null) {
            $this->returned = collect([]);
        }
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
        return view('livewire.notificationstoggler');
    }
}
