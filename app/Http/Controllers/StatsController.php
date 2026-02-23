<?php

namespace App\Http\Controllers;

use App\Models\DsvBudget;
use App\Models\ProjectProposal;
use App\Services\Budget\ReCalcBudget;
use Faker\Guesser\Name;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use Statamic\View\View;

class StatsController extends Controller
{
    public function preapproved()
    {
        $available_states = [
            'head_approved',
            'fo_approved',
            'final_approved',
            'sent'
            ];

        $proposals = ProjectProposal::whereIn('status_stage1', $available_states)->count();
        $budget = DsvBudget::find(1);

        //Check first if there are stats to show
        if( $proposals > 0 && !empty(json_decode($budget->funding_org, true))) {
            //Recalculate
            $this->recalcBudget();
        } else {
            $viewData['breadcrumb'] = 'Stats are unavailable';
            return $this->createView('stats.unavailable', 'mylayout', $viewData);
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

        //Research Subject preapproved and budget
        foreach ($budget->research_area as $key => $dsv) {
            $labels[] = strlen($key) > 20 ? substr($key, 0, 17) . '...' : $key; // Limit to 20 characters
            $preapproved[] = $dsv['preapproved'] ?? 0;
            $commited_sek[] = $dsv['budget_sek'] ?? 0;
            $commited_eur[] = $dsv['budget_eur'] ?? 0;
            $commited_usd[] = $dsv['budget_usd'] ?? 0;
            $cost_sek[] = $dsv['cost_sek'] ?? 0;
            $cost_eur[] = $dsv['cost_eur'] ?? 0;
            $cost_usd[] = $dsv['cost_usd'] ?? 0;
            $phd[] = $dsv['phd'] ?? 0;
        }

        //Funding Agency
        foreach (json_decode($budget->funding_org, true) as $key => $fundingOrg) {
            $org[] = strlen($key) > 20 ? substr($key, 0, 17) . '...' : $key; // Limit to 20 characters
            $orgStats[] = $fundingOrg;
        }

        //Preapproved
        $chart['researchsubject_preapproved'] = Chartjs::build()
            ->name('barChartPreapproved')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "PreApproved",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $preapproved,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        //Commited budget SEK
        $chart['researchsubject_commited_sek'] = Chartjs::build()
            ->name('barChartCommited_sek')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Commited budget (SEK)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $commited_sek,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        //Commited budget EUR
        $chart['researchsubject_commited_eur'] = Chartjs::build()
            ->name('barChartCommited_eur')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Commited budget (EUR)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $commited_eur,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        //Commited budget USD
        $chart['researchsubject_commited_usd'] = Chartjs::build()
            ->name('barChartCommited_usd')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Commited budget (USD)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $commited_usd,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        //PhD budget
        $chart['researchsubject_phd'] = Chartjs::build()
            ->name('barChartPhD')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "PhD years",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $phd,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        //Chart Agency funding
        $chart['agency'] = Chartjs::build()
            ->name('barChartAgency')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($org)
            ->datasets([
                [
                    "label" => "Funding organization",
                    'backgroundColor' => 'rgba(128, 0, 128, 1)',
                    'borderWidth' => 1,
                    'data' => $orgStats,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        $breadcrumb = 'Stats';
        return $this->createView('stats.proposal_stats', 'mylayout', compact('chart', 'breadcrumb'));
    }

    public function approved()
    {
        $available_states = [
            'granted'
        ];

        $proposals = ProjectProposal::whereIn('status_stage1', $available_states)->count();
        $budget = DsvBudget::find(1);

        //Check first if there are stats to show
        if( $proposals > 0 && !empty(json_decode($budget->funding_org, true))) {
            //Recalculate
            $this->recalcBudget();
        } else {
            $viewData['breadcrumb'] = 'Stats are unavailable';
            return $this->createView('stats.unavailable', 'mylayout', $viewData);
        }

        $labels = [];
        $commited_sek = [];
        $commited_eur = [];
        $commited_usd = [];
        $cost_sek = [];
        $cost_eur = [];
        $cost_usd = [];
        $phd = [];
        $granted_sek = [];
        $granted_eur = [];
        $granted_usd = [];
        $promised_sek = [];
        $promised_eur = [];
        $promised_usd = [];

        //Research Subject approved and budget
        foreach ($budget->research_area as $key => $dsv) {
            $labels[] = strlen($key) > 20 ? substr($key, 0, 17) . '...' : $key; // Limit to 20 characters
            $commited_sek[] = $dsv['budget_sek'] ?? 0;
            $commited_eur[] = $dsv['budget_eur'] ?? 0;
            $commited_usd[] = $dsv['budget_usd'] ?? 0;
            $cost_sek[] = $dsv['cost_sek'] ?? 0;
            $cost_eur[] = $dsv['cost_eur'] ?? 0;
            $cost_usd[] = $dsv['cost_usd'] ?? 0;
            $phd[] = $dsv['phd'] ?? 0;
            $granted_sek[] = $dsv['granted_sek'] ?? 0;
            $granted_eur[] = $dsv['granted_eur'] ?? 0;
            $granted_usd[] = $dsv['granted_usd'] ?? 0;
            $cofinancing_promised_sek[] = $dsv['cofinancing_promised_sek'] ?? 0;
            $cofinancing_promised_eur[] = $dsv['cofinancing_promised_eur'] ?? 0;
            $cofinancing_promised_usd[] = $dsv['cofinancing_promised_usd'] ?? 0;
        }

        //Funding Agency
        foreach (json_decode($budget->funding_org, true) as $key => $fundingOrg) {
            $org[] = strlen($key) > 20 ? substr($key, 0, 17) . '...' : $key; // Limit to 20 characters
            $orgStats[] = $fundingOrg;
        }

        //Granted
        $chart['researchsubject_granted_sek'] = Chartjs::build()
            ->name('barChartGrantedSEK')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Granted (SEK)",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $granted_sek,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        $chart['researchsubject_promised_sek'] = Chartjs::build()
            ->name('barChartPromisedSEK')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Cofinacing promised (SEK)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $cofinancing_promised_sek,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        $chart['researchsubject_granted_eur'] = Chartjs::build()
            ->name('barChartGrantedEur')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Granted (EUR)",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $granted_eur,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        $chart['researchsubject_promised_eur'] = Chartjs::build()
            ->name('barChartPromisedEUR')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Cofinacing promised (EUR)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $cofinancing_promised_eur,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);
        $chart['researchsubject_granted_usd'] = Chartjs::build()
            ->name('barChartGrantedUSD')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Granted (USD)",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $granted_usd,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        $chart['researchsubject_promised_usd'] = Chartjs::build()
            ->name('barChartPromisedUSD')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Cofinacing promised (USD)",
                    'backgroundColor' => 'rgba(0, 255, 0, 1)',
                    'borderWidth' => 1,
                    'data' => $cofinancing_promised_usd,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        //PhD budget
        $chart['researchsubject_phd'] = Chartjs::build()
            ->name('barChartPhD')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "PhD years",
                    'backgroundColor' => 'rgba(0, 123, 255, 1)', // Blue
                    'borderWidth' => 1,
                    'data' => $phd,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        //Chart Agency funding
        $chart['agency'] = Chartjs::build()
            ->name('barChartAgency')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($org)
            ->datasets([
                [
                    "label" => "Funding organization",
                    'backgroundColor' => 'rgba(128, 0, 128, 1)',
                    'borderWidth' => 1,
                    'data' => $orgStats,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left' // Assign to left y-axis
                ],

            ]);

        $breadcrumb = 'Stats';

        return $this->createView('stats.proposal_approved', 'mylayout', compact('chart', 'breadcrumb'));
    }

    public function recalcBudget()
    {
        $calc = new ReCalcBudget();
        $calc->scan();

        return redirect()->back();
    }

    private function createView($template, $layout, $data)
    {
        return (new View)->template($template)->layout($layout)->with($data);
    }
}
