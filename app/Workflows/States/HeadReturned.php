<?php

namespace App\Workflows\States;

class HeadReturned extends DashboardState
{
    public static $name = 'head_returned';

    public function status(): string
    {
        return 'head_returned';
    }
}
