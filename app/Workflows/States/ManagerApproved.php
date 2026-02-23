<?php

namespace App\Workflows\States;

class ManagerApproved extends DashboardState
{
    public static $name = 'manager_approved';

    public function status(): string
    {
        return 'manager_approved';
    }
}
