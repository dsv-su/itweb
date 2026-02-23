<?php

namespace App\Workflows\States;

class FinalReturned extends DashboardState
{
    public static $name = 'final_returned';

    public function status(): string
    {
        return 'final_returned';
    }
}
