<?php

namespace App\Workflows\States;

class ViceApproved extends DashboardState
{
    public static $name = 'vice_approved';

    public function status(): string
    {
        return 'vice_approved';
    }
}
