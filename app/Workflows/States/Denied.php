<?php

namespace App\Workflows\States;

class Denied extends DashboardState
{
    public static $name = 'denied';

    public function status(): string
    {
        return 'denied';
    }
}
