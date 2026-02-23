<?php

namespace App\Workflows\States;

class ManagerDenied extends DashboardState
{
    public static $name = 'manager_denied';

    public function status(): string
    {
        return 'manager_denied';
    }
}
