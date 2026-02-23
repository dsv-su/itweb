<?php

namespace App\Workflows\States;

class FinalDenied extends DashboardState
{
    public static $name = 'final_denied';

    public function status(): string
    {
        return 'final_denied';
    }
}
