<?php

namespace App\Workflows\States;

class Submitted extends DashboardState
{
    public static $name = 'submitted';

    public function status(): string
    {
        return 'submitted';
    }
}
