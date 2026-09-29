<?php

namespace App\Livewire;

use App\Traits\DashboardRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Requestnotifications extends Component
{
    use DashboardRequests;

    public function render()
    {
        abort_unless(Auth::check(), 401);

        return view('livewire.requestnotifications', [
            'requests' => $this->Dashboardtask(Auth::id()),
        ]);
    }
}
