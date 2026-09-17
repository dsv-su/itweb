<?php

namespace App\Services\Stats;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

/** Investigator counts are shared across overview, unit and research subject views */
class PrincipalInvestigatorCharts
{
    public function __construct(private StatsChartFactory $charts) {}

    public function counts(int $year, bool $granted): array
    {
        // The subquery counts each proposal once, even with duplicate dashboard entries.
        $proposals = ProjectProposal::query()
            ->whereIn('id', Dashboard::query()->select('request_id')->whereIn('state', $granted ? ['granted'] : ['sent', 'granted']))
            ->where('pp->submission_deadline', '>=', "$year-01-01")
            ->where('pp->submission_deadline', '<=', "$year-12-31")
            ->get(['pp']);

        $counts = [];
        foreach ($proposals as $proposal) {
            $name = trim((string) ($proposal->pp['principal_investigator'] ?? '')) ?: 'Unknown principal investigator';
            $counts[$name] = ($counts[$name] ?? 0) + 1;
        }
        ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);

        return $counts;
    }

    public function grouped(array $groupCounts, string $name = 'barChartPrincipalInvestigatorsGrouped', string $axisLabel = 'Proposals', bool $wholeNumbers = true, array $investigatorColors = []): Builder
    {
        // Keep investigators together under their unit or research subject.
        $labels = [];
        foreach ($groupCounts as $group => $counts) {
            foreach ($counts as $investigator => $count) {
                $labels[] = [$group, $investigator];
            }
        }

        $datasets = [];
        $offset = 0;
        foreach ($groupCounts as $group => $counts) {
            $values = array_fill(0, count($labels), null);
            $colors = array_fill(0, count($labels), 'transparent');
            foreach ($counts as $investigator => $count) {
                $colors[$offset] = $investigatorColors[$investigator] ?? 'transparent';
                $values[$offset++] = $count;
            }
            $hue = fmod(count($datasets) * 137.508, 360);
            $datasets[] = [
                'label' => $group,
                'data' => $values,
                'backgroundColor' => $investigatorColors ? $colors : "hsl($hue, 65%, 50%)",
                'borderRadius' => 4,
                'maxBarThickness' => 24,
            ];
        }

        // Horizontal rows leave room for names; sparse stacked datasets share one row per name
        $height = max(300, count($labels) * 52 + 100);
        // Tick settings must serialize as an object; an empty array breaks Chart.js

        return Chartjs::build()
            ->name($name)
            ->type('bar')
            ->size(['width' => 1000, 'height' => $height])
            ->labels($labels)
            ->datasets($datasets)
            ->options([
                'indexAxis' => 'y',
                'aspectRatio' => 1000 / $height,
                'plugins' => ['legend' => ['position' => 'bottom', 'display' => $investigatorColors === []]],
                'scales' => [
                    'x' => ['stacked' => true, 'beginAtZero' => true, 'ticks' => ['precision' => $wholeNumbers ? 0 : 2], 'title' => ['display' => true, 'text' => $axisLabel]],
                    'y' => ['stacked' => true, 'ticks' => ['autoSkip' => false], 'grid' => ['display' => false]],
                ],
            ]);
    }

    public function budgets(array $currencies): array
    {
        // Build one palette before filtering so a name keeps its color across currencies and units.
        $names = [];
        foreach ($currencies as $groups) {
            foreach ($groups as $investigators) {
                foreach ($investigators as $investigator => $amount) {
                    if ((float) $amount !== 0.0) {
                        $names[$investigator] = true;
                    }
                }
            }
        }
        ksort($names, SORT_NATURAL | SORT_FLAG_CASE);
        $colors = [];
        foreach (array_keys($names) as $index => $investigator) {
            $hue = fmod($index * 137.508, 360);
            $colors[$investigator] = "hsl($hue, 65%, 50%)";
        }

        $charts = [];
        foreach ($currencies as $currency => $amounts) {
            // Only plot investigators with a non-zero budget in this currency.
            $amounts = array_filter(array_map(
                fn ($investigators) => array_filter($investigators, fn ($amount) => (float) $amount !== 0.0),
                $amounts
            ));
            if ($amounts === []) {
                continue;
            }

            $charts[$currency] = $this->grouped(
                $amounts,
                'barChartInvestigatorBudget'.$currency,
                'Requested budget ('.$currency.')',
                false,
                $colors
            );
        }

        return $charts;
    }

    public function build(int $year, bool $granted): Builder
    {
        $counts = $this->counts($year, $granted);

        // Preserve full names and use whole-number ticks for proposal counts
        return $this->charts->bar(
            'barChartPrincipalInvestigators',
            array_keys($counts),
            $granted ? 'Granted proposals' : 'Submitted proposals',
            array_values($counts),
            'rgba(0, 123, 255, 1)'
        )->options(['scales' => ['y-left' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]]]);
    }
}
