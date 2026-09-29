<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Userrequeststoggler extends Component
{
    public function render()
    {
        abort_unless(Auth::check(), 401);

        return view('livewire.userrequeststoggler', [
            'user_requests' => Dashboard::where('user_id', Auth::id())->orderByDesc('created_at')->get(),
        ]);
    }
}
