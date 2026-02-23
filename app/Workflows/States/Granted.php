<?php

namespace App\Workflows\States;

class Granted extends DashboardState
{
    public static $name = 'granted';

    public function status(): string
    {
        return 'granted';
    }
}
