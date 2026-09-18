<?php

namespace App\Services\Stats;

use App\Models\DsvBudget;

/** Converts the annual ReCalcBudget totals into charts for the overview views */
class OverviewCharts
{
    public function __construct(private StatsChartFactory $charts) {}

    public function build(DsvBudget $budget, bool $granted, bool $fullLabels = false): array
    {
        // Every dataset aligned with the same research subject labels
        $areas = $budget->research_area ?? [];
        $labels = array_map(fn ($label) => $fullLabels ? (string) $label : $this->shortLabel((string) $label), array_keys($areas));
        $definitions = $granted ? $this->grantedDefinitions() : $this->committedDefinitions();
        $charts = [];

        // Each definition maps a view key to its canvas ID, source field, label and color
        foreach ($definitions as $key => [$name, $field, $label, $color]) {
            $values = array_map(fn ($area) => $area[$field] ?? 0, array_values($areas));
            $charts[$key] = $this->charts->bar($name, $labels, $label, $values, $color);
        }

        // Agency totals use their own labels
        $agencies = $budget->funding_org ?? [];
        $charts['agency'] = $this->charts->bar(
            'barChartAgency',
            array_map(fn ($label) => $fullLabels ? (string) $label : $this->shortLabel((string) $label), array_keys($agencies)),
            $granted ? 'Funding organization' : 'Funding Agency',
            array_values($agencies),
            'rgba(128, 0, 128, 1)'
        );

        return $charts;
    }

    // Overview charts, render their keys in the corresponding Blade view
    private function committedDefinitions(): array
    {
        return [
            'researchsubject_preapproved' => ['barChartPreapproved', 'preapproved', 'PreApproved', 'rgba(0, 123, 255, 1)'],
            'researchsubject_commited_sek' => ['barChartCommited_sek', 'budget_sek', 'Commited budget (SEK)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_commited_eur' => ['barChartCommited_eur', 'budget_eur', 'Commited budget (EUR)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_commited_usd' => ['barChartCommited_usd', 'budget_usd', 'Commited budget (USD)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_phd' => ['barChartPhD', 'phd', 'PhD years', 'rgba(0, 123, 255, 1)'],
        ];
    }

    private function grantedDefinitions(): array
    {
        return [
            'researchsubject_granted_sek' => ['barChartGrantedSEK', 'granted_sek', 'Granted (SEK)', 'rgba(0, 123, 255, 1)'],
            'researchsubject_promised_sek' => ['barChartPromisedSEK', 'cofinancing_promised_sek', 'Cofinacing promised (SEK)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_granted_eur' => ['barChartGrantedEur', 'granted_eur', 'Granted (EUR)', 'rgba(0, 123, 255, 1)'],
            'researchsubject_promised_eur' => ['barChartPromisedEUR', 'cofinancing_promised_eur', 'Cofinacing promised (EUR)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_granted_usd' => ['barChartGrantedUSD', 'granted_usd', 'Granted (USD)', 'rgba(0, 123, 255, 1)'],
            'researchsubject_promised_usd' => ['barChartPromisedUSD', 'cofinancing_promised_usd', 'Cofinacing promised (USD)', 'rgba(0, 255, 0, 1)'],
            'researchsubject_phd' => ['barChartPhD', 'phd', 'PhD years', 'rgba(0, 123, 255, 1)'],
        ];
    }

    private function shortLabel(string $label, int $max = 20): string
    {
        return mb_strlen($label) > $max ? (mb_substr($label, 0, $max - 3).'...') : $label;
    }
}
