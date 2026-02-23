<?php

namespace App\Workflows\States;

class Sent extends DashboardState
{
    public static $name = 'sent';

    public function status(): string
    {
        return 'sent';
    }
}
