<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Returnednotifications extends Component
{
    public function read($id)
    {
        Dashboard::where('user_id', Auth::id())->whereKey($id)->update(['status' => 'read']);
    }

    public function render()
    {
        abort_unless(Auth::check(), 401);

        return view('livewire.returnednotifications', [
            'returned' => Dashboard::where('user_id', Auth::id())->where('status', 'unread')
                ->whereIn('state', ['manager_returned', 'manager_denied', 'fo_returned', 'fo_denied',
                    'head_returned', 'head_denied', 'vice_returned', 'vice_denied'])->get(),
        ]);
    }
}
