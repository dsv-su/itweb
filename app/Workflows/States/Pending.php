<?php

namespace App\Workflows\States;

class Pending extends DashboardState
{
    public static $name = 'pending';

    public function status(): string
    {
        return 'pending';
    }
}
