<?php

namespace App\Workflows\States;

class FOApproved extends DashboardState
{
    public static $name = 'fo_approved';

    public function status(): string
    {
        return 'fo_approved';
    }
}
