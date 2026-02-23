<?php

namespace App\Workflows\States;

class HeadDenied extends DashboardState
{
    public static $name = 'head_denied';

    public function status(): string
    {
        return 'head_denied';
    }
}
