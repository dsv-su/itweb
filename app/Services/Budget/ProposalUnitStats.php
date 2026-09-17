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
        return $this->summary($year, $states)['counts'];
    }

    public function summary(int $year, array $states, string $breakdown = 'unit'): array
    {
        $proposals = ProjectProposal::query()
            ->whereIn('id', Dashboard::query()->select('request_id')->whereIn('state', $states))
            ->where('pp->submission_deadline', '>=', "$year-01-01")
            ->where('pp->submission_deadline', '<=', "$year-12-31")
            ->get(['pp']);

        $units = collect();
        if ($breakdown === 'unit') {
            $headIds = $proposals->flatMap(fn ($proposal) => Arr::wrap($proposal->pp['unit_head'] ?? []))
                ->filter()->unique()->values();
            $units = User::query()->whereIn('id', $headIds)->pluck('unit', 'id');
        }
        $counts = [];
        $phdYears = [];
        $budgets = [];
        $cofinancing = [];
        $agencies = [];
        $investigators = [];
        $investigatorBudgets = [];

        foreach ($proposals as $proposal) {
            if ($breakdown === 'research_area') {
                $proposalUnits = collect([trim((string) ($proposal->pp['research_area'] ?? '')) ?: 'Unknown research subject']);
            } else {
                // Count a proposal once per distinct unit, even when several heads share it.
                $proposalUnits = collect(Arr::wrap($proposal->pp['unit_head'] ?? []))
                    ->map(fn ($id) => trim((string) ($units[$id] ?? '')) ?: 'Unknown unit')
                    ->unique();
                if ($proposalUnits->isEmpty()) {
                    $proposalUnits->push('Unknown unit');
                }
            }
            $phdAmount = (float) ($proposal->pp['budget_phd'] ?? 0);
            $currency = strtoupper($proposal->pp['currency'] ?? 'sek');
            $amount = (float) ($proposal->pp['budget_dsv'] ?? 0);
            $cofinancingAmount = (float) ($proposal->pp['cofinancing_needed'] ?? 0);
            $agency = trim((string) ($proposal->pp['funding_organization'] ?? '')) ?: 'Unknown funding agency';
            $investigator = trim((string) ($proposal->pp['principal_investigator'] ?? '')) ?: 'Unknown principal investigator';
            foreach ($proposalUnits as $unit) {
                $investigatorBudgets[$currency][$unit][$investigator] = ($investigatorBudgets[$currency][$unit][$investigator] ?? 0) + $amount;
                $investigators[$unit][$investigator] = ($investigators[$unit][$investigator] ?? 0) + 1;
                $agencies[$agency][$unit] = ($agencies[$agency][$unit] ?? 0) + 1;
                $phdYears[$unit] = ($phdYears[$unit] ?? 0) + $phdAmount;
                $counts[$unit] = ($counts[$unit] ?? 0) + 1;
                $cofinancing[$currency][$unit] = ($cofinancing[$currency][$unit] ?? 0) + $cofinancingAmount;
                $budgets[$currency][$unit] = ($budgets[$currency][$unit] ?? 0) + $amount;
            }
        }

        ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);
        $phdYears = array_replace(array_fill_keys(array_keys($counts), 0.0), $phdYears);

        ksort($budgets);
        foreach ($budgets as $currency => $amounts) {
            $budgets[$currency] = array_replace(array_fill_keys(array_keys($counts), 0.0), $amounts);
        }

        ksort($cofinancing);
        foreach ($cofinancing as $currency => $amounts) {
            $cofinancing[$currency] = array_replace(array_fill_keys(array_keys($counts), 0.0), $amounts);
        }

        ksort($agencies, SORT_NATURAL | SORT_FLAG_CASE);
        foreach ($agencies as $agency => $unitCounts) {
            $agencies[$agency] = array_replace(array_fill_keys(array_keys($counts), 0), $unitCounts);
        }

        // Group investigators using the same distinct unit membership as other charts.
        ksort($investigators, SORT_NATURAL | SORT_FLAG_CASE);
        foreach ($investigators as $unit => $investigatorCounts) {
            ksort($investigatorCounts, SORT_NATURAL | SORT_FLAG_CASE);
            $investigators[$unit] = $investigatorCounts;
        }

        // Align all currencies with the count chart so unit colors and rows stay consistent.
        ksort($investigatorBudgets);
        foreach ($investigatorBudgets as $currency => $amounts) {
            $aligned = [];
            foreach ($investigators as $unit => $names) {
                $aligned[$unit] = array_replace(array_fill_keys(array_keys($names), 0.0), $amounts[$unit] ?? []);
            }
            $investigatorBudgets[$currency] = $aligned;
        }

        return ['counts' => $counts, 'budgets' => $budgets, 'agencies' => $agencies, 'cofinancing' => $cofinancing, 'phd_years' => $phdYears, 'investigators' => $investigators, 'investigator_budgets' => $investigatorBudgets];
    }
}
