<?php

namespace App\Services\Budget;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Support\Arr;

class ProposalUnitStats
{
    public function counts(int $year, array $states): array
    {
        $proposals = ProjectProposal::query()
            ->whereIn('id', Dashboard::query()->select('request_id')->whereIn('state', $states))
            ->where('pp->submission_deadline', '>=', "$year-01-01")
            ->where('pp->submission_deadline', '<=', "$year-12-31")
            ->get(['pp']);

        $headIds = $proposals->flatMap(fn ($proposal) => Arr::wrap($proposal->pp['unit_head'] ?? []))
            ->filter()->unique()->values();
        $units = User::query()->whereIn('id', $headIds)->pluck('unit', 'id');
        $counts = [];

        foreach ($proposals as $proposal) {
            // Count a proposal once per distinct unit, even when several heads share it.
            $proposalUnits = collect(Arr::wrap($proposal->pp['unit_head'] ?? []))
                ->map(fn ($id) => trim((string) ($units[$id] ?? '')) ?: 'Unknown unit')
                ->unique();
            if ($proposalUnits->isEmpty()) {
                $proposalUnits->push('Unknown unit');
            }
            foreach ($proposalUnits as $unit) {
                $counts[$unit] = ($counts[$unit] ?? 0) + 1;
            }
        }

        ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);

        return $counts;
    }
}
