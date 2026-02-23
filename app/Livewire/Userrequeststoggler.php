<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Statamic\Auth\User;

class Userrequeststoggler extends Component
{
    public $auth_user;
    public $user_roles;
    public $user_requests;

    public function mount()
    {
        $this->user_roles = $this->getUserRoles();
        $this->user_requests = Dashboard::where('user_id', $this->auth_user)->orderBy('created_at', 'desc')->get();
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
        return view('livewire.userrequeststoggler');
    }
}
