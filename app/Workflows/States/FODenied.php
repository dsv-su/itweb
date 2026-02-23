<?php

namespace App\Workflows\States;

class FODenied extends DashboardState
{
    public static $name = 'fo_denied';

    public function status(): string
    {
        return 'fo_denied';
    }
}
