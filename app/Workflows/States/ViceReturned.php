<?php

namespace App\Workflows\States;

class ViceReturned extends DashboardState
{
    public static $name = 'vice_returned';

    public function status(): string
    {
        return 'vice_returned';
    }
}
