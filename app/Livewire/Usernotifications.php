<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Usernotifications extends Component
{
    public function read($id)
    {
        Dashboard::where('user_id', Auth::id())->where('state', 'fo_approved')->whereKey($id)->update(['status' => 'read']);
    }

    public function render()
    {
        abort_unless(Auth::check(), 401);

        return view('livewire.usernotifications', [
            'user_requests' => Dashboard::with(['travel', 'proposal'])->where('user_id', Auth::id())->orderByDesc('created_at')->limit(10)->get(),
        ]);
    }
}
