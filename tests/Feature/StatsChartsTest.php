<?php

namespace Tests\Feature;

use App\Models\DsvBudget;
use App\Services\Stats\BreakdownCharts;
use App\Services\Stats\OverviewCharts;
use Tests\TestCase;

class StatsChartsTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function ($app) {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite.database', ':memory:');
            $app['config']->set('statamic.eloquent-driver.connection', 'sqlite');
        });
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


    public function test_budget_colors_follow_investigators_across_currencies_and_units(): void
    {
        $charts = app(\App\Services\Stats\PrincipalInvestigatorCharts::class)->budgets([
            'EUR' => ['Alpha' => ['Alice' => 0, 'Bob' => 10]],
            'SEK' => ['Alpha' => ['Alice' => 20, 'Bob' => 30], 'Beta' => ['Bob' => 40]],
        ]);
        $bob = $charts['EUR']->get('datasets.0.backgroundColor.0');
        $this->assertSame($bob, $charts['SEK']->get('datasets.0.backgroundColor.1'));
        $this->assertSame($bob, $charts['SEK']->get('datasets.1.backgroundColor.2'));
        $this->assertNotSame($bob, $charts['SEK']->get('datasets.0.backgroundColor.0'));
        $this->assertSame([['Alpha', 'Bob']], $charts['EUR']->get('labels'));
        $this->assertFalse($charts['EUR']->get('options.plugins.legend.display'));
    }

    public function test_overview_charts_keep_counts_currencies_and_grants_separate(): void
    {
        $budget = new DsvBudget;
        $budget->research_area = [
            'Computer science' => ['preapproved' => 3, 'budget_sek' => 100, 'granted_sek' => 60, 'cofinancing_promised_sek' => 20, 'phd' => 1.5],
            'Information systems' => [],
        ];
        $budget->funding_org = ['Agency' => 3];
        $builder = app(OverviewCharts::class);
        $committed = $builder->build($budget, false);
        $granted = $builder->build($budget, true);

        $this->assertCount(6, $committed);
        $this->assertCount(8, $granted);
        $this->assertSame(['Computer science', 'Information systems'], $committed['researchsubject_preapproved']->get('labels'));
        $this->assertSame([3, 0], $committed['researchsubject_preapproved']->get('datasets.0.data'));
        $this->assertSame([100, 0], $committed['researchsubject_commited_sek']->get('datasets.0.data'));
        $this->assertSame([0, 0], $committed['researchsubject_commited_eur']->get('datasets.0.data'));
        $this->assertSame([60, 0], $granted['researchsubject_granted_sek']->get('datasets.0.data'));
        $this->assertSame([20, 0], $granted['researchsubject_promised_sek']->get('datasets.0.data'));
        $this->assertSame([1.5, 0], $granted['researchsubject_phd']->get('datasets.0.data'));
        $this->assertSame([3], $committed['agency']->get('datasets.0.data'));
    }

    public function test_breakdowns_preserve_chart_values_axes_and_group_labels(): void
    {
        $summary = [
            'counts' => ['Alpha' => 2, 'Beta' => 1],
            'budgets' => ['SEK' => ['Alpha' => 100.0, 'Beta' => 0.0]],
            'cofinancing' => ['SEK' => ['Alpha' => 20.0, 'Beta' => 0.0]],
            'phd_years' => ['Alpha' => 1.5, 'Beta' => 0.0],
            'agencies' => ['Agency' => ['Alpha' => 2, 'Beta' => 1]],
        ];
        $builder = app(BreakdownCharts::class);
        foreach (['unit' => 'unit', 'research_area' => 'research subject'] as $breakdown => $label) {
            $charts = $builder->build($summary, false, $breakdown);
            $this->assertSame('Submitted proposals per '.$label, $charts['title']);
            $this->assertSame([2, 1], $charts['chart']->get('datasets.0.data'));
            $this->assertSame(0, $charts['chart']->get('options.scales.y-left.ticks.precision'));
            $this->assertSame([100.0, 0.0], $charts['budgetCharts']['SEK']->get('datasets.0.data'));
            $this->assertSame([20.0, 0.0], $charts['cofinancingCharts']['SEK']->get('datasets.0.data'));
            $this->assertSame([1.5, 0.0], $charts['phdChart']->get('datasets.0.data'));
            $this->assertSame(['Alpha', 'Beta'], $charts['agencyChart']->get('labels'));
            $this->assertSame([2, 1], $charts['agencyChart']->get('datasets.0.data'));
            $this->assertTrue($charts['agencyChart']->get('options.scales.x.stacked'));
            $this->assertSame('Granted proposals per '.$label, $builder->build($summary, true, $breakdown)['title']);
        }
    }
}
