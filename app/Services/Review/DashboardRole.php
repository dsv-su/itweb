<?php

namespace App\Services\Review;

use App\Models\Dashboard;
use App\Models\User;

class DashboardRole
{
    private $dashboard;
    //private $reviewer;

    public function __construct(Dashboard $dashboard, User $reviewer)
    {
        $this->dashboard = $dashboard;
        $this->reviewer = $reviewer;
    }

    public function check()
    {
        // Map states to role fields
        $roles = [
            'complete' => 'head_id',
            'head_approved' => 'fo_id',
            'fo_approved' => 'vice_id',
        ];

        // Get the state as a string
        $currentState = (string) $this->dashboard->state;
        // && in_array($this->reviewer->id, $this->dashboard->unit_heads, true
        if (array_key_exists($currentState, $roles)) {
            return $this->getRoleFromState($currentState);
        }

        return false;
    }

    private function getRoleFromState($state)
    {
        $roleMapping = [
            'complete' => 'head',
            'head_approved' => 'fo',
            'fo_approved' => 'vice_final',
        ];

        return $roleMapping[$state] ?? false; // Return the role or false if not found
    }
}

