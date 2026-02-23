<?php

namespace App\Workflows\Partials;

use App\Models\Dashboard;

class CheckRoleforApprove
{
    public $dashboard;

    public function isSameUserManager($request): bool
    {
        // Retrieve dashboard instance
        $dashboard = Dashboard::find($request);

        // Ensure the dashboard exists before accessing properties
        if (!$dashboard) {
            return false;
        }

        // Check if the user and manager are the same
        return $dashboard->user_id === $dashboard->manager_id;
    }


    public function isSameManagerHead($request): bool
    {
        // Retrieve dashboard instance
        $dashboard = Dashboard::find($request);

        // Ensure the dashboard exists before accessing properties
        if (!$dashboard) {
            return false;
        }

        // Check if manager and head are the same
        return $dashboard->manager_id === $dashboard->head_id;
    }


}
