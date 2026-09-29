<?php

namespace App\Livewire;

use App\Traits\DashboardIndicator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Mobileindicator extends Component
{
    use DashboardIndicator;

    public function render()
    {
        abort_unless(Auth::check(), 401);

        return view('livewire.mobileindicator', [
            'dashboard' => $this->DashboardIndicator(Auth::id()),
        ]);
    }
}
