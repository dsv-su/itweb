<?php

namespace App\Workflows\Transitions;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\TravelRequest;
use Workflow\Activity;

class StateUpdateTransition extends Activity
{
    public function execute(int $dashboardId): void
    {
        $dashboard = Dashboard::findOrFail($dashboardId);

        $map = [
            'travelrequest'   => [TravelRequest::class, 'state'],
            'projectproposal' => [ProjectProposal::class, 'status_stage1'],
        ];

        if (!isset($map[$dashboard->type])) {
            throw new \InvalidArgumentException("Unsupported dashboard type: {$dashboard->type}");
        }

        [$modelClass, $field] = $map[$dashboard->type];

        $modelClass::query()
            ->whereKey($dashboard->request_id)
            ->update([$field => $dashboard->state]);
    }
}

