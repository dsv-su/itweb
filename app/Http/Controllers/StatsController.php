<?php

namespace App\Http\Controllers;

use App\Exports\StatsExport;
use App\Models\ProjectProposal;
use App\Services\Budget\ProposalUnitStats;
use App\Services\Budget\ReCalcBudget;
use App\Services\Stats\BreakdownCharts;
use App\Services\Stats\OverviewCharts;
use App\Services\Stats\PrincipalInvestigatorCharts;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Statamic\View\View;

class StatsController extends Controller
{
    public function preapproved(Request $request)
    {
        return $this->show($request, false);
    }

    public function approved(Request $request)
    {
        return $this->show($request, true);
    }

    public function recalcBudget(bool $redirect = true)
    {
        $calc = new ReCalcBudget;
        $calc->scan();

        if ($redirect) {
            return redirect()->back();
        }

        return null;
    }

    private function show(Request $request, bool $granted)
    {
        $validated = $request->validate([
            'download' => ['nullable', 'in:xlsx'],
            'year' => ['nullable', 'integer', 'between:1000,9999'],
            'breakdown' => ['nullable', 'in:overview,unit,research_area'],
        ]);
        $fromYear = (int) ($validated['year'] ?? now()->year);
        $years = $this->availableYears($fromYear);
        $breakdown = $validated['breakdown'] ?? 'overview';
        $data = ['fromYear' => $fromYear, 'years' => $years, 'breadcrumb' => 'Stats'];

        if ($breakdown !== 'overview') {
            // Sent breakdowns include subsequent grants, but not internal approvals.
            $states = $granted ? ['granted'] : ['sent', 'granted'];
            $summary = app(ProposalUnitStats::class)->summary($fromYear, $states, $breakdown);
            $data += app(BreakdownCharts::class)->build($summary, $granted, $breakdown);
            $template = 'stats.proposal_units';
        } else {
            $budget = app(ReCalcBudget::class)->scan($fromYear);
            $template = $granted ? 'stats.proposal_approved' : 'stats.proposal_stats';
            if ($budget->preapproved_total <= 0) {
                $template = 'stats.unavailable';
                $data['breadcrumb'] = 'Stats are unavailable';
            } else {
                $data['chart'] = app(OverviewCharts::class)->build($budget, $granted, ($validated['download'] ?? null) === 'xlsx');
            }
        }

        if ($breakdown !== 'overview') {
            $data['investigatorChart'] = app(PrincipalInvestigatorCharts::class)->grouped($summary['investigators']);
            $data['investigatorBudgetCharts'] = app(PrincipalInvestigatorCharts::class)->budgets($summary['investigator_budgets']);
        } elseif ($template !== 'stats.unavailable') {
            $data['investigatorChart'] = app(PrincipalInvestigatorCharts::class)->build($fromYear, $granted);
        }

        if (($validated['download'] ?? null) === 'xlsx') {
            abort_if($template === 'stats.unavailable' || ($breakdown !== 'overview' && empty($data['counts'])), 404, 'No chart data available for this selection.');

            return Excel::download(
                new StatsExport($data, $granted, $breakdown, $fromYear),
                'proposal-stats-'.($granted ? 'granted' : 'committed').'-'.$fromYear.'-'.$breakdown.'.xlsx'
            );
        }

        $data['hasChartData'] = $template !== 'stats.unavailable' && ($breakdown === 'overview' || ! empty($data['counts']));

        return (new View)->template($template)->layout('mylayout')->with($data);
    }

    private function availableYears(int $selectedYear): array
    {
        return ProjectProposal::query()
            ->whereNotNull('pp->submission_deadline')
            ->pluck('pp->submission_deadline as submission_deadline')
            ->filter(fn ($deadline) => is_string($deadline) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline))
            ->map(fn ($deadline) => (int) substr($deadline, 0, 4))
            ->push(now()->year, $selectedYear)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }
}
