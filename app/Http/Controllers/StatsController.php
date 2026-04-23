<?php

namespace App\Http\Controllers;

use App\Models\DsvBudget;
use App\Models\ProjectProposal;
use App\Services\Budget\ReCalcBudget;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Statamic\View\View;

class StatsController extends Controller
{
    private const PREAPPROVED_STATES = [
        //'head_approved',
        //'fo_approved',
        'final_approved',
        'sent',
    ];

    private const YEAR = 2026;

    private const APPROVED_STATES = [
        'granted',
    ];

    public function preapproved()
    {
        $fromYear = self::YEAR;

        $start = $fromYear . '-01-01';
        $end   = $fromYear . '-12-31';

        $proposals = ProjectProposal::query()
            ->whereIn('status_stage1', self::PREAPPROVED_STATES)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(pp, '$.submission_deadline')) BETWEEN ? AND ?", [$start, $end])
            ->count();

        $budget = DsvBudget::find(1);

        $fundingOrg = $budget ? json_decode($budget->funding_org, true) : null;

        if ($proposals <= 0 || empty($fundingOrg)) {
            $viewData['breadcrumb'] = 'Stats are unavailable';
            $viewData['fromYear'] = $fromYear;
            return $this->createView('stats.unavailable', 'mylayout', $viewData);
        }

        // Recalculate without redirect side effects
        $this->recalcBudget(false);

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
        return $this->createView('stats.proposal_stats', 'mylayout', compact('chart', 'breadcrumb', 'fromYear'));
    }

    public function approved()
    {
        $fromYear = self::YEAR;

        $start = $fromYear . '-01-01';
        $end   = $fromYear . '-12-31';

        $proposals = ProjectProposal::query()
            ->whereIn('status_stage1', self::PREAPPROVED_STATES)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(pp, '$.submission_deadline')) BETWEEN ? AND ?", [$start, $end])
            ->count();
        $budget = DsvBudget::find(1);

        $fundingOrg = $budget ? json_decode($budget->funding_org, true) : null;

        if ($proposals <= 0 || empty($fundingOrg)) {
            $viewData['breadcrumb'] = 'Stats are unavailable';
            $viewData['fromYear'] = $fromYear;
            return $this->createView('stats.unavailable', 'mylayout', $viewData);
        }

        // Recalculate without redirect side effects
        $this->recalcBudget(false);

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
        return $this->createView('stats.proposal_approved', 'mylayout', compact('chart', 'breadcrumb', 'fromYear'));
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
