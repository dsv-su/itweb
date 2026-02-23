<?php

namespace App\Services\Budget;

use App\Models\Dashboard;
use App\Models\DsvBudget;
use App\Models\ProjectProposal;
use Illuminate\Support\Facades\Artisan;

class ReCalcBudget
{
    public function scan()
    {
        // Reset the budget table
        Artisan::call('clear-areas');

        //Dashboard states to include
        $available_states = [
            'head_approved', 'fo_approved', 'final_approved',
            'sent', 'granted'
        ];

        //Dashboard sent states
        $sent_states = [
            'sent', 'granted'
        ];

        // Fetch all matching proposals
        $dashboardRequests = Dashboard::whereIn('state', $available_states)
            ->pluck('request_id');
        $proposals = ProjectProposal::whereIn('id', $dashboardRequests)->get();
        $budget    = DsvBudget::find(1);

        //Fetch and count sent proposals
        $dashboardSentRequests = Dashboard::whereIn('state', $sent_states)->count();

        //Fetch and count granted proposals
        $dashboardGrantedRequests = Dashboard::whereIn('state', ['granted'])->count();

        // Initialize per-area accumulators
        $counts             = [];
        $committedPerArea   = ['sek'=>[], 'eur'=>[], 'usd'=>[]];
        $costPerArea        = ['sek'=>[], 'eur'=>[], 'usd'=>[]];
        $grantedPerArea     = ['sek'=>[], 'eur'=>[], 'usd'=>[]];
        $promisedPerArea    = ['sek'=>[], 'eur'=>[], 'usd'=>[]];
        $phdPerArea         = [];

        // Initialize grand totals
        $totals = [
            'preapproved'    => 0,
            'dsv_budget'     => ['sek'=>0,'eur'=>0,'usd'=>0],
            'project_budget' => ['sek'=>0,'eur'=>0,'usd'=>0],
            'cost_total'     => ['sek'=>0,'eur'=>0,'usd'=>0],
            'granted_dsv'    => ['sek'=>0,'eur'=>0,'usd'=>0],
            'promised_dsv'   => ['sek'=>0,'eur'=>0,'usd'=>0],
            'phd_total'      => 0,
        ];

        // Start with whatever the model has (or an empty array)
        $researchAreas = $budget->getAttribute('research_area') ?? [];

        foreach ($proposals as $proposal) {
            // pp should already be cast to array; if not, decode manually:
            $pp              = is_string($proposal->pp)
                ? json_decode($proposal->pp, true)
                : $proposal->pp;
            $area            = $pp['research_area']       ?? 'unknown';
            $cur             = $pp['currency']            ?? 'sek'; // 'sek', 'eur' or 'us'
            $dsvBud          = $pp['budget_dsv']          ?? 0;
            $projBud         = $pp['budget_project']      ?? 0;
            $cofinNeed       = $pp['cofinancing_needed']  ?? 0;
            $amountGranted   = $pp['granted']            ?? 0;
            $amountPromised  = $pp['cofinanced_promised'] ?? 0;
            $phdCount        = $pp['budget_phd']         ?? 0;

            // Ensure a structure exists for this area
            if (!isset($researchAreas[$area])) {
                $researchAreas[$area] = [
                    'preapproved'              => 0,
                    'budget_sek'               => 0,
                    'budget_eur'               => 0,
                    'budget_usd'               => 0,
                    'phd'                      => 0,
                    'cost_sek'                 => 0,
                    'cost_eur'                 => 0,
                    'cost_usd'                 => 0,
                    'granted_sek'              => 0,
                    'granted_eur'              => 0,
                    'granted_usd'              => 0,
                    'cofinancing_promised_sek' => 0,
                    'cofinancing_promised_eur' => 0,
                    'cofinancing_promised_usd' => 0,
                ];
            }

            // Tally per-area counts
            $counts[$area] = ($counts[$area] ?? 0) + 1;
            $committedPerArea[$cur][$area]  = ($committedPerArea[$cur][$area]  ?? 0) + $dsvBud;
            $costPerArea     [$cur][$area]  = ($costPerArea     [$cur][$area]  ?? 0) + $cofinNeed;
            $grantedPerArea  [$cur][$area]  = ($grantedPerArea  [$cur][$area]  ?? 0) + $amountGranted;
            $promisedPerArea [$cur][$area]  = ($promisedPerArea [$cur][$area]  ?? 0) + $amountPromised;
            $phdPerArea[$area]              = ($phdPerArea[$area]              ?? 0) + $phdCount;

            // Update grand totals
            $totals['preapproved']++;
            $totals['dsv_budget'][$cur]     += $dsvBud;
            $totals['project_budget'][$cur] += $projBud;
            $totals['cost_total'][$cur]     += $cofinNeed;
            $totals['granted_dsv'][$cur]    += $amountGranted;
            $totals['promised_dsv'][$cur]   += $amountPromised;
            $totals['phd_total']            += $phdCount;
        }

        // Write the per-area numbers back into the researchAreas array
        foreach ($counts as $area => $cnt) {
            $researchAreas[$area]['preapproved'] = $cnt;
            foreach (['sek','eur','usd'] as $c) {
                $researchAreas[$area]["budget_{$c}"]               = $committedPerArea[$c][$area]  ?? 0;
                $researchAreas[$area]["cost_{$c}"]                 = $costPerArea     [$c][$area]  ?? 0;
                $researchAreas[$area]["granted_{$c}"]              = $grantedPerArea  [$c][$area]  ?? 0;
                $researchAreas[$area]["cofinancing_promised_{$c}"] = $promisedPerArea [$c][$area]  ?? 0;
            }
            $researchAreas[$area]['phd'] = $phdPerArea[$area] ?? 0;
        }

        $budget->setAttribute('research_area', $researchAreas);

        // Count funding organizations
        $orgNames = $proposals
            ->pluck('pp.funding_organization')
            ->filter()
            ->toArray();
        $fundingStats = array_count_values($orgNames);
        $budget->setAttribute('funding_org', $fundingStats);

        // Set the modified budget back to the model
        $budget->setAttribute('preapproved_total', $totals['preapproved']);
        $budget->setAttribute('sent_total', $dashboardSentRequests);
        $budget->setAttribute('granted_total', $dashboardGrantedRequests);
        $budget->setAttribute('budget_dsv_total_sek', $totals['dsv_budget']['sek']);
        $budget->setAttribute('budget_dsv_total_eur', $totals['dsv_budget']['eur']);
        $budget->setAttribute('budget_dsv_total_usd', $totals['dsv_budget']['usd']);
        $budget->setAttribute('budget_project_total_sek', $totals['project_budget']['sek']);
        $budget->setAttribute('budget_project_total_eur', $totals['project_budget']['eur']);
        $budget->setAttribute('budget_project_total_usd', $totals['project_budget']['usd']);
        $budget->setAttribute('phd_total', $totals['phd_total']);
        $budget->setAttribute('cost_total_sek', $totals['cost_total']['sek']);
        $budget->setAttribute('cost_total_eur', $totals['cost_total']['eur']);
        $budget->setAttribute('cost_total_usd', $totals['cost_total']['usd']);
        $budget->setAttribute('granted_total_sek', $totals['granted_dsv']['sek']);
        $budget->setAttribute('granted_total_eur', $totals['granted_dsv']['eur']);
        $budget->setAttribute('granted_total_usd', $totals['granted_dsv']['usd']);
        $budget->setAttribute('cofinanced_total_sek', $totals['promised_dsv']['sek']);
        $budget->setAttribute('cofinanced_total_eur', $totals['promised_dsv']['eur']);
        $budget->setAttribute('cofinanced_total_usd', $totals['promised_dsv']['usd']);

        // Save everything
        $budget->save();
    }



}
