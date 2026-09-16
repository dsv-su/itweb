<?php

namespace App\Http\Controllers;

use App\Models\ProjectProposal;
use App\Services\Budget\ReCalcBudget;
use App\Services\Budget\ProposalUnitStats;
use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Statamic\View\View;

class StatsController extends Controller
{
    public function preapproved(Request $request)
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'between:1000,9999'],
            'breakdown' => ['nullable', 'in:overview,unit'],
        ]);
        $fromYear = (int) ($validated['year'] ?? now()->year);
        $years = $this->availableYears($fromYear);
        if (($validated['breakdown'] ?? 'overview') === 'unit') {
            return $this->perUnit($fromYear, $years, false);
        }
        $budget = (new ReCalcBudget())->scan($fromYear);
        $fundingOrg = $budget->funding_org ?? [];

        if ($budget->preapproved_total <= 0) {
            $breadcrumb = 'Stats are unavailable';
            return $this->createView('stats.unavailable', 'mylayout', compact('breadcrumb', 'fromYear', 'years'));
        }

        $labels = [];
        $preapproved = [];
        $commited_sek = [];
        $commited_eur = [];
        $commited_usd = [];
        $cost_sek = [];
        $cost_eur = [];
        $cost_usd = [];
        $phd = [];

        foreach (($budget->research_area ?? []) as $key => $dsv) {
            $labels[] = $this->shortLabel((string) $key);
            $preapproved[] = $dsv['preapproved'] ?? 0;
            $commited_sek[] = $dsv['budget_sek'] ?? 0;
            $commited_eur[] = $dsv['budget_eur'] ?? 0;
            $commited_usd[] = $dsv['budget_usd'] ?? 0;
            $cost_sek[] = $dsv['cost_sek'] ?? 0;
            $cost_eur[] = $dsv['cost_eur'] ?? 0;
            $cost_usd[] = $dsv['cost_usd'] ?? 0;
            $phd[] = $dsv['phd'] ?? 0;
        }

        $org = [];
        $orgStats = [];
        foreach ($fundingOrg as $key => $value) {
            $org[] = $this->shortLabel((string) $key);
            $orgStats[] = $value;
        }

        $chart = [];
        $chart['researchsubject_preapproved'] = $this->buildBarChart(
            'barChartPreapproved',
            $labels,
            'PreApproved',
            $preapproved,
            'rgba(0, 123, 255, 1)'
        );

        $chart['researchsubject_commited_sek'] = $this->buildBarChart(
            'barChartCommited_sek',
            $labels,
            'Commited budget (SEK)',
            $commited_sek,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_commited_eur'] = $this->buildBarChart(
            'barChartCommited_eur',
            $labels,
            'Commited budget (EUR)',
            $commited_eur,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_commited_usd'] = $this->buildBarChart(
            'barChartCommited_usd',
            $labels,
            'Commited budget (USD)',
            $commited_usd,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_phd'] = $this->buildBarChart(
            'barChartPhD',
            $labels,
            'PhD years',
            $phd,
            'rgba(0, 123, 255, 1)'
        );

        $chart['agency'] = $this->buildBarChart(
            'barChartAgency',
            $org,
            'Funding Agency',
            $orgStats,
            'rgba(128, 0, 128, 1)'
        );

        $breadcrumb = 'Stats';
        return $this->createView('stats.proposal_stats', 'mylayout', compact('chart', 'breadcrumb', 'fromYear', 'years'));
    }

    public function approved(Request $request)
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'between:1000,9999'],
            'breakdown' => ['nullable', 'in:overview,unit'],
        ]);
        $fromYear = (int) ($validated['year'] ?? now()->year);
        $years = $this->availableYears($fromYear);
        if (($validated['breakdown'] ?? 'overview') === 'unit') {
            return $this->perUnit($fromYear, $years, true);
        }
        $budget = (new ReCalcBudget())->scan($fromYear);
        $fundingOrg = $budget->funding_org ?? [];

        if ($budget->preapproved_total <= 0) {
            $breadcrumb = 'Stats are unavailable';
            return $this->createView('stats.unavailable', 'mylayout', compact('breadcrumb', 'fromYear', 'years'));
        }

        $labels = [];
        $phd = [];
        $granted_sek = [];
        $granted_eur = [];
        $granted_usd = [];
        $cofinancing_promised_sek = [];
        $cofinancing_promised_eur = [];
        $cofinancing_promised_usd = [];

        foreach (($budget->research_area ?? []) as $key => $dsv) {
            $labels[] = $this->shortLabel((string) $key);
            $phd[] = $dsv['phd'] ?? 0;
            $granted_sek[] = $dsv['granted_sek'] ?? 0;
            $granted_eur[] = $dsv['granted_eur'] ?? 0;
            $granted_usd[] = $dsv['granted_usd'] ?? 0;
            $cofinancing_promised_sek[] = $dsv['cofinancing_promised_sek'] ?? 0;
            $cofinancing_promised_eur[] = $dsv['cofinancing_promised_eur'] ?? 0;
            $cofinancing_promised_usd[] = $dsv['cofinancing_promised_usd'] ?? 0;
        }

        $org = [];
        $orgStats = [];
        foreach ($fundingOrg as $key => $value) {
            $org[] = $this->shortLabel((string) $key);
            $orgStats[] = $value;
        }

        $chart = [];
        $chart['researchsubject_granted_sek'] = $this->buildBarChart(
            'barChartGrantedSEK',
            $labels,
            'Granted (SEK)',
            $granted_sek,
            'rgba(0, 123, 255, 1)'
        );

        $chart['researchsubject_promised_sek'] = $this->buildBarChart(
            'barChartPromisedSEK',
            $labels,
            'Cofinacing promised (SEK)',
            $cofinancing_promised_sek,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_granted_eur'] = $this->buildBarChart(
            'barChartGrantedEur',
            $labels,
            'Granted (EUR)',
            $granted_eur,
            'rgba(0, 123, 255, 1)'
        );

        $chart['researchsubject_promised_eur'] = $this->buildBarChart(
            'barChartPromisedEUR',
            $labels,
            'Cofinacing promised (EUR)',
            $cofinancing_promised_eur,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_granted_usd'] = $this->buildBarChart(
            'barChartGrantedUSD',
            $labels,
            'Granted (USD)',
            $granted_usd,
            'rgba(0, 123, 255, 1)'
        );

        $chart['researchsubject_promised_usd'] = $this->buildBarChart(
            'barChartPromisedUSD',
            $labels,
            'Cofinacing promised (USD)',
            $cofinancing_promised_usd,
            'rgba(0, 255, 0, 1)'
        );

        $chart['researchsubject_phd'] = $this->buildBarChart(
            'barChartPhD',
            $labels,
            'PhD years',
            $phd,
            'rgba(0, 123, 255, 1)'
        );

        $chart['agency'] = $this->buildBarChart(
            'barChartAgency',
            $org,
            'Funding organization',
            $orgStats,
            'rgba(128, 0, 128, 1)'
        );

        $breadcrumb = 'Stats';
        return $this->createView('stats.proposal_approved', 'mylayout', compact('chart', 'breadcrumb', 'fromYear', 'years'));
    }

    public function recalcBudget(bool $redirect = true)
    {
        $calc = new ReCalcBudget();
        $calc->scan();

        if ($redirect) {
            return redirect()->back();
        }

        return null;
    }

    private function perUnit(int $fromYear, array $years, bool $granted)
    {
        $counts = (new ProposalUnitStats())->counts($fromYear, $granted ? ['granted'] : ['sent', 'granted']);
        $title = $granted ? 'Granted proposals per unit' : 'Submitted proposals per unit';
        $description = $granted ? 'Proposals granted by the funder.' : 'Proposals sent to the funder, including those subsequently granted.';
        $chart = $this->buildBarChart('barChartUnits', array_keys($counts), $granted ? 'Granted proposals' : 'Submitted proposals', array_values($counts), 'rgba(0, 123, 255, 1)')
            ->options(['scales' => ['y-left' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]]]);
        $breadcrumb = 'Stats';

        return $this->createView('stats.proposal_units', 'mylayout', compact('fromYear', 'years', 'counts', 'title', 'description', 'chart', 'breadcrumb'));
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

    private function buildBarChart(string $name, array $labels, string $label, array $data, string $color)
    {
        return Chartjs::build()
            ->name($name)
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    'label' => $label,
                    'backgroundColor' => $color,
                    'borderWidth' => 1,
                    'data' => $data,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left',
                ],
            ]);
    }

    private function shortLabel(string $label, int $max = 20): string
    {
        return mb_strlen($label) > $max ? (mb_substr($label, 0, $max - 3) . '...') : $label;
    }

    private function createView($template, $layout, $data)
    {
        return (new View)->template($template)->layout($layout)->with($data);
    }
}
