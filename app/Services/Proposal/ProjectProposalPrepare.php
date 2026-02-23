<?php

namespace App\Services\Proposal;

use App\Models\ProjectProposal;
use App\Models\ResearchArea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectProposalPrepare
{
    public function prepareProjectProposalData(?string $id = null)
    {
        $roleIdsUnitHead = $this->getUserIdsByGroup('enhetschef');
        $unitheads = User::whereIn('id', $roleIdsUnitHead)->get();
        $research_areas = ResearchArea::all();

        $proposal = $id
            ? ProjectProposal::firstOrNew(['id' => $id])
            : new ProjectProposal();

        $userId = Auth::id();

        if (! $proposal->exists) {
            $proposal->fill([
                'user_id' => $userId,
                'name' => '',
                'created' => now()->startOfDay()->timestamp,
                'status_stage1' => 'pending',
                'status_stage2' => 'pending',
                'status_stage3' => 'pending',
                'files' => [],
            ]);
            $proposal->save();
        }

        return compact('unitheads', 'research_areas', 'proposal');
    }

    private function getUserIdsByGroup($group)
    {
        return DB::table('group_user')->where('group_id', $group)->pluck('user_id');
    }
}
