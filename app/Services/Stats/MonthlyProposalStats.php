<?php

namespace App\Services\Stats;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use Carbon\CarbonImmutable;

class MonthlyProposalStats
{
    public function build(CarbonImmutable $month): array
    {
        $start = $month->startOfMonth();
        $end = $start->addMonth();
        // Match the annual statistics: submission deadline and current sent/granted state.
        $proposals = ProjectProposal::query()
            ->whereIn('id', Dashboard::query()->select('request_id')->whereIn('state', ['sent', 'granted']))
            ->where('pp->submission_deadline', '>=', $start->toDateString())
            ->where('pp->submission_deadline', '<', $end->toDateString())
            ->get(['id', 'pp']);
        $grantedIds = Dashboard::query()->where('state', 'granted')
            ->whereIn('request_id', $proposals->modelKeys())->pluck('request_id')->all();

        $stats = [
            'month' => $start->format('F Y'),
            'period' => $start->format('j M').' – '.$end->subDay()->format('j M Y'),
            'total' => $proposals->count(),
            'granted' => 0,
            'awaiting' => 0,
            'phd_years' => 0.0,
            'budgets' => [],
            'research_subjects' => [],
            'funding_organizations' => [],
        ];

        foreach ($proposals as $proposal) {
            $data = $proposal->pp ?? [];
            $granted = in_array($proposal->id, $grantedIds, true);
            $stats[$granted ? 'granted' : 'awaiting']++;
            $stats['phd_years'] += (float) ($data['budget_phd'] ?? 0);
            $currency = strtoupper(trim((string) ($data['currency'] ?? ''))) ?: 'Unspecified';
            $stats['budgets'][$currency] ??= ['requested' => 0.0, 'granted' => 0.0, 'cofinancing' => 0.0];
            $amount = (float) ($data['budget_dsv'] ?? 0);
            $stats['budgets'][$currency]['requested'] += $amount;
            $stats['budgets'][$currency]['granted'] += $granted ? $amount : 0;
            $stats['budgets'][$currency]['cofinancing'] += (float) ($data['cofinancing_needed'] ?? 0);
            foreach (['research_subjects' => 'research_area', 'funding_organizations' => 'funding_organization'] as $group => $field) {
                $label = trim((string) ($data[$field] ?? '')) ?: 'Unspecified';
                $stats[$group][$label] = ($stats[$group][$label] ?? 0) + 1;
            }
        }

        ksort($stats['budgets']);
        arsort($stats['research_subjects']);
        arsort($stats['funding_organizations']);

        return $stats;
    }
}
