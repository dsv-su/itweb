<?php

namespace App\Workflows\States;

class FinalApproved extends DashboardState
{
    public static $name = 'final_approved';

    public function status(): string
    {
        return 'final_approved';
    }
}
