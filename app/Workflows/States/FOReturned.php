<?php

namespace App\Workflows\States;

class FOReturned extends DashboardState
{
    public static $name = 'fo_returned';

    public function status(): string
    {
        return 'fo_returned';
    }
}
