<?php

namespace App\Workflows\States;

class Complete extends DashboardState
{
    public static $name = 'complete';

    public function status(): string
    {
        return 'complete';
    }
}
