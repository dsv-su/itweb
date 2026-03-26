<?php

namespace App\Workflows\Partials;

use App\Models\Dashboard;
use Workflow\WorkflowStub;

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
        return (string)$dashboard->user_id === (string)$dashboard->manager_id;
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
        return (string)$dashboard->manager_id === (string)$dashboard->head_id;
    }


}
