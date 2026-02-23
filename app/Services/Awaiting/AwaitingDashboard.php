<?php

namespace App\Services\Awaiting;

use App\Models\Dashboard;
use App\Models\User;

class AwaitingDashboard
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function proposals()
    {
        $user = $this->user;

        return Dashboard::where('type', 'projectproposal')
            ->where(function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->where('state', 'complete')
                        ->whereJsonContains('unit_head_approved', [$this->user->id => 0])
                        ->whereHas('proposal', function ($projectQuery) {
                            $projectQuery->whereJsonLength('files', '>=', 2);
                        }); // Ensure 'files' contains at least 2 files
                })
                ->orWhere(function ($subQuery) use ($user) {
                    $subQuery->where('state', 'head_approved')
                        ->where('fo_id', $user->id);
                })
                ->orWhere(function ($subQuery) use ($user) {
                    $subQuery->where('state', 'fo_approved')
                        ->where('vice_id', $user->id);
                });
            })
            ->pluck('request_id');
    }
}
