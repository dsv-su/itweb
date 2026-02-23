<?php

namespace App\Workflows\States;

class HeadApproved extends DashboardState
{
    public static $name = 'head_approved';

    public function status(): string
    {
        return 'head_approved';
    }
}
