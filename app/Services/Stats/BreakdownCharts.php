<?php

namespace App\Services\Stats;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

/** Chart presentation shared by the unit and research subject breakdowns */
class BreakdownCharts
{
    public function __construct(private StatsChartFactory $charts) {}

    public function build(array $summary, bool $granted, string $breakdown): array
    {
        // ProposalUnitStats supplies totals already filtered by year/state and grouped
        $groupLabel = $breakdown === 'research_area' ? 'research subject' : 'unit';
        $counts = $summary['counts'];
        // Monetary amounts in separate charts for each currency
        $budgetCharts = $this->currencyCharts($summary['budgets'], 'barChartUnitBudget', 'Requested budget', 'rgba(22, 163, 74, 1)');
        $cofinancingCharts = $this->currencyCharts($summary['cofinancing'], 'barChartUnitCofinancing', 'Cofinancing promised', 'rgba(147, 51, 234, 1)');
        $phdChart = $this->charts->bar(
            'barChartUnitPhdYears',
            array_keys($summary['phd_years']),
            'Committed PhD student years',
            array_values($summary['phd_years']),
            'rgba(234, 88, 12, 1)'
        )->options(['scales' => ['y-left' => ['beginAtZero' => true]]]);
        $agencyChart = $this->agencyChart($summary['agencies'], array_keys($counts));
        $title = ($granted ? 'Granted proposals per ' : 'Submitted proposals per ').$groupLabel;
        $description = $granted ? 'Proposals granted by the funder.' : 'Proposals sent to the funder, including those subsequently granted.';
        // Proposal counts use whole-number ticks; PhD years can be fractional.
        $chart = $this->charts->bar('barChartUnits', array_keys($counts), $granted ? 'Granted proposals' : 'Submitted proposals', array_values($counts), 'rgba(0, 123, 255, 1)')
            ->options(['scales' => ['y-left' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]]]);

        // These keys are the variables used by stats.proposal_units.
        return compact('counts', 'title', 'groupLabel', 'description', 'chart', 'budgetCharts', 'cofinancingCharts', 'phdChart', 'agencyChart');
    }

    private function currencyCharts(array $currencies, string $name, string $label, string $color): array
    {
        $charts = [];
        foreach ($currencies as $currency => $amounts) {
            $charts[$currency] = $this->charts->bar(
                $name.$currency,
                array_keys($amounts),
                $label.' ('.$currency.')',
                array_values($amounts),
                $color
            )->options(['scales' => ['y-left' => ['beginAtZero' => true]]]);
        }

        return $charts;
    }

    private function agencyChart(array $agencies, array $labels): Builder
    {
        // Each agency becomes one colored segment in each groups stacked bar
        $agencyDatasets = [];
        foreach ($agencies as $agency => $unitCounts) {
            // Spread successive colors around as agencies are added
            $hue = fmod(count($agencyDatasets) * 137.508, 360);
            $agencyDatasets[] = [
                'label' => $agency,
                'data' => array_values($unitCounts),
                'backgroundColor' => "hsl($hue, 65%, 50%)",
                'yAxisID' => 'y-left',
            ];
        }

        return Chartjs::build()
            ->name('barChartUnitAgencies')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets($agencyDatasets)
            ->options(['scales' => [
                'x' => ['stacked' => true],
                'y-left' => ['stacked' => true, 'beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ]]);
    }
}
