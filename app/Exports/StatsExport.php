<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatsExport implements WithMultipleSheets
{
    public function __construct(private array $data, private bool $granted, private string $breakdown, private int $year) {}

    public function sheets(): array
    {
        $charts = [];
        if ($this->breakdown === 'overview') {
            $overview = $this->data['chart'];
            if ($this->granted) {
                foreach (['sek', 'eur', 'usd'] as $currency) {
                    $charts['Granted '.strtoupper($currency)] = $overview['researchsubject_granted_'.$currency];
                    $charts['Cofinancing '.strtoupper($currency)] = $overview['researchsubject_promised_'.$currency];
                }
            } else {
                $charts['Proposals by subject'] = $overview['researchsubject_preapproved'];
                foreach (['sek', 'eur', 'usd'] as $currency) {
                    $charts['Committed budget '.strtoupper($currency)] = $overview['researchsubject_commited_'.$currency];
                }
                $charts['PhD years'] = $overview['researchsubject_phd'];
                $charts['Funding agency'] = $overview['agency'];
            }
        } else {
            $charts['Proposals by group'] = $this->data['chart'];
            foreach (['budgetCharts' => 'Requested budget ', 'cofinancingCharts' => 'Cofinancing '] as $key => $prefix) {
                foreach ($this->data[$key] as $currency => $chart) {
                    $charts[$prefix.strtoupper($currency)] = $chart;
                }
            }
            $charts['PhD years'] = $this->data['phdChart'];
            $charts['Funding agency'] = $this->data['agencyChart'];
        }
        $charts['Principal investigators'] = $this->data['investigatorChart'];
        if ($this->breakdown !== 'overview') {
            foreach ($this->data['investigatorBudgetCharts'] as $currency => $chart) {
                $charts['Investigator budget '.strtoupper($currency)] = $chart;
            }
        }

        $context = ($this->granted ? 'Granted' : 'Committed').' proposals | Submission deadlines in '.$this->year.' | '.match ($this->breakdown) {
            'unit' => 'Per unit (proposals may appear in multiple units)',
            'research_area' => 'Per research subject',
            default => 'Overview',
        };
        $sheets = [];
        foreach ($charts as $title => $chart) {
            $sheets[] = new StatsChartSheet($title, $chart, $context, count($sheets) + 1);
        }

        return $sheets;
    }
}
