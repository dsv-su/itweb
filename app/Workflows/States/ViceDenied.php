<?php

namespace App\Workflows\States;

class ViceDenied extends DashboardState
{
    public static $name = 'vice_denied';

    public function status(): string
    {
        return 'vice_denied';
    }
}
