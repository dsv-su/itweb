<?php

namespace App\Workflows\States;

class ManagerReturned extends DashboardState
{
    public static $name = 'manager_returned';

    public function status(): string
    {
        return 'manager_returned';
    }
}
