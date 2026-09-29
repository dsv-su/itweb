<?php

namespace App\Services\Review;

use App\Models\Dashboard;
use App\Models\User;

class DashboardRole
{
    public function __construct(private Dashboard $dashboard, private User $reviewer) {}

    public function check(): string|false
    {
        // Re-check persisted assignments even when the caller holds an older model.
        $dashboard = $this->dashboard->fresh();
        if (! $dashboard || $dashboard->type !== 'projectproposal') {
            return false;
        }

        $userId = $this->reviewer->id;

        return match ((string) $dashboard->state) {
            'complete' => in_array($userId, (array) $dashboard->unit_heads, true) ? 'head' : false,
            'head_approved' => $dashboard->fo_id === $userId ? 'fo' : false,
            'fo_approved' => $dashboard->vice_id === $userId ? 'vice_final' : false,
            default => false,
        };
    }
}
