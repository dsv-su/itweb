<?php

namespace Tests\Feature;

use App\Services\Budget\ReCalcBudget;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProposalStatsYearTest extends TestCase
{
    public function test_available_years_read_json_deadlines(): void
    {
        $this->travelTo(now()->setDate(2026, 9, 16));
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('pp');
        });

        foreach (['2024-12-31', '2025-01-01', '2025-12-31', null, 'invalid'] as $id => $deadline) {
            DB::table('project_proposals')->insert([
                'id' => (string) $id,
                'pp' => json_encode(['submission_deadline' => $deadline]),
            ]);
        }

        $method = new \ReflectionMethod(\App\Http\Controllers\StatsController::class, 'availableYears');
        $this->assertSame(
            [2026, 2025, 2024, 2023],
            $method->invoke(new \App\Http\Controllers\StatsController(), 2023),
        );
    }

    public function test_unit_counts_follow_year_state_and_distinct_units(): void
    {
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('pp');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('state');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('unit')->nullable();
        });
        DB::table('users')->insert([
            ['id' => 1, 'unit' => 'Alpha'],
            ['id' => 2, 'unit' => 'Alpha'],
            ['id' => 3, 'unit' => 'Beta'],
            ['id' => 4, 'unit' => null],
        ]);
        $fixtures = [
            ['2026-01-01', 'sent', [1, 2, 3]],
            ['2026-12-31', 'granted', '1'],
            ['2026-06-01', 'sent', [999, 4]],
            ['2026-06-01', 'sent', []],
            ['2025-12-31', 'sent', [3]],
            ['2027-01-01', 'granted', [3]],
            ['2026-06-01', 'submitted', [3]],
            ['2026-06-01', 'final_approved', [3]],
        ];
        foreach ($fixtures as $id => [$deadline, $state, $heads]) {
            DB::table('project_proposals')->insert([
                'id' => (string) $id,
                'pp' => json_encode([
                    'submission_deadline' => $deadline,
                    'unit_head' => $heads,
                    'principal_investigator' => $id === 3 ? ' ' : ($id === 1 ? 'Bob' : ' Alice '),
                    'research_area' => $id === 3 ? ' ' : ($id === 1 ? 'Information systems' : 'Computer science'),
                    'budget_dsv' => $id === 3 ? null : '100.50',
                    'budget_phd' => $id === 3 ? null : ($id === 1 ? '2.5' : '1.25'),
                    'cofinancing_needed' => $id === 3 ? null : '25.75',
                    'cofinanced_promised' => 999,
                    'currency' => $id === 1 ? 'eur' : 'sek',
                    'funding_organization' => $id === 3 ? ' ' : ($id === 1 ? 'Agency B' : 'Agency A'),
                ]),
            ]);
            DB::table('dashboards')->insert(['request_id' => (string) $id, 'state' => $state]);
        }
        // Duplicate dashboard entries must not duplicate a proposal's contribution.
        DB::table('dashboards')->insert(['request_id' => '0', 'state' => 'sent']);
        $investigators = app(\App\Services\Stats\PrincipalInvestigatorCharts::class);
        $this->assertSame(['Alice' => 2, 'Bob' => 1, 'Unknown principal investigator' => 1], $investigators->counts(2026, false));
        $this->assertSame(['Bob' => 1], $investigators->counts(2026, true));
        $this->assertSame([], $investigators->counts(2024, false));
        $investigatorChart = $investigators->build(2026, false);
        $this->assertSame(['Alice', 'Bob', 'Unknown principal investigator'], $investigatorChart->get('labels'));
        $this->assertSame([2, 1, 1], $investigatorChart->get('datasets.0.data'));
        $this->assertSame(0, $investigatorChart->get('options.scales.y-left.ticks.precision'));
        $stats = new \App\Services\Budget\ProposalUnitStats();
        $unitInvestigators = $stats->summary(2026, ['sent', 'granted'])['investigators'];
        $investigatorBudgets = $stats->summary(2026, ['sent', 'granted'])['investigator_budgets'];
        $this->assertSame([
            'EUR' => ['Alpha' => ['Alice' => 0.0, 'Bob' => 100.5], 'Beta' => ['Alice' => 0.0], 'Unknown unit' => ['Alice' => 0.0, 'Unknown principal investigator' => 0.0]],
            'SEK' => ['Alpha' => ['Alice' => 100.5, 'Bob' => 0.0], 'Beta' => ['Alice' => 100.5], 'Unknown unit' => ['Alice' => 100.5, 'Unknown principal investigator' => 0.0]],
        ], $investigatorBudgets);
        $this->assertSame(['EUR' => ['Alpha' => ['Bob' => 100.5]]], $stats->summary(2026, ['granted'])['investigator_budgets']);
        $budgetCharts = $investigators->budgets($investigatorBudgets);
        $this->assertSame([100.5, null, null], $budgetCharts['SEK']->get('datasets.0.data'));
        $this->assertSame([['Alpha', 'Alice'], ['Beta', 'Alice'], ['Unknown unit', 'Alice']], $budgetCharts['SEK']->get('labels'));
        $this->assertSame([['Alpha', 'Bob']], $budgetCharts['EUR']->get('labels'));
        $this->assertSame([100.5], $budgetCharts['EUR']->get('datasets.0.data'));
        $this->assertCount(1, $budgetCharts['EUR']->get('datasets'));
        $this->assertSame([], $investigators->budgets(['EUR' => ['Alpha' => ['Alice' => 0, 'Bob' => null, 'Carol' => '']]]));
        $this->assertSame('Requested budget (SEK)', $budgetCharts['SEK']->get('options.scales.x.title.text'));
        $this->assertSame(['precision' => 2], $budgetCharts['SEK']->get('options.scales.x.ticks'));
        $options = json_decode(json_encode($budgetCharts['SEK']->get('options')));
        $this->assertInstanceOf(\stdClass::class, $options->scales->x->ticks);
        $this->assertNotSame($budgetCharts['SEK']->name, $budgetCharts['EUR']->name);

        $this->assertSame([
            'Alpha' => ['Alice' => 1, 'Bob' => 1],
            'Beta' => ['Alice' => 1],
            'Unknown unit' => ['Alice' => 1, 'Unknown principal investigator' => 1],
        ], $unitInvestigators);
        $this->assertSame(['Alpha' => ['Bob' => 1]], $stats->summary(2026, ['granted'])['investigators']);
        $groupedChart = $investigators->grouped($unitInvestigators);
        $this->assertSame([['Alpha', 'Alice'], ['Alpha', 'Bob'], ['Beta', 'Alice'], ['Unknown unit', 'Alice'], ['Unknown unit', 'Unknown principal investigator']], $groupedChart->get('labels'));
        $this->assertSame([1, 1, null, null, null], $groupedChart->get('datasets.0.data'));
        $this->assertSame([null, null, 1, null, null], $groupedChart->get('datasets.1.data'));
        $this->assertSame('y', $groupedChart->get('options.indexAxis'));
        $this->assertSame([], $investigators->grouped([])->get('datasets'));

        $this->assertSame(['Alpha' => 2, 'Beta' => 1, 'Unknown unit' => 2], $stats->counts(2026, ['sent', 'granted']));
        $this->assertSame(['Alpha' => 1], $stats->counts(2026, ['granted']));
        $this->assertSame([], $stats->counts(2024, ['sent', 'granted']));
        $this->assertSame([
            'EUR' => ['Alpha' => 100.5, 'Beta' => 0.0, 'Unknown unit' => 0.0],
            'SEK' => ['Alpha' => 100.5, 'Beta' => 100.5, 'Unknown unit' => 100.5],
        ], $stats->summary(2026, ['sent', 'granted'])['budgets']);
        $this->assertSame(['EUR' => ['Alpha' => 100.5]], $stats->summary(2026, ['granted'])['budgets']);
        $this->assertSame([], $stats->summary(2024, ['sent', 'granted'])['budgets']);
        $this->assertSame([
            'Agency A' => ['Alpha' => 1, 'Beta' => 1, 'Unknown unit' => 1],
            'Agency B' => ['Alpha' => 1, 'Beta' => 0, 'Unknown unit' => 0],
            'Unknown funding agency' => ['Alpha' => 0, 'Beta' => 0, 'Unknown unit' => 1],
        ], $stats->summary(2026, ['sent', 'granted'])['agencies']);
        $this->assertSame(['Agency B' => ['Alpha' => 1]], $stats->summary(2026, ['granted'])['agencies']);
        $this->assertSame([], $stats->summary(2024, ['sent', 'granted'])['agencies']);
        $this->assertSame([
            'EUR' => ['Alpha' => 25.75, 'Beta' => 0.0, 'Unknown unit' => 0.0],
            'SEK' => ['Alpha' => 25.75, 'Beta' => 25.75, 'Unknown unit' => 25.75],
        ], $stats->summary(2026, ['sent', 'granted'])['cofinancing']);
        $this->assertSame(['EUR' => ['Alpha' => 25.75]], $stats->summary(2026, ['granted'])['cofinancing']);
        $this->assertSame([], $stats->summary(2024, ['sent', 'granted'])['cofinancing']);
        $this->assertSame(['Alpha' => 3.75, 'Beta' => 1.25, 'Unknown unit' => 1.25], $stats->summary(2026, ['sent', 'granted'])['phd_years']);
        $this->assertSame(['Alpha' => 2.5], $stats->summary(2026, ['granted'])['phd_years']);
        $this->assertSame([], $stats->summary(2024, ['sent', 'granted'])['phd_years']);

        $research = $stats->summary(2026, ['sent', 'granted'], 'research_area');
        $this->assertSame([
            'Computer science' => ['Alice' => 2],
            'Information systems' => ['Bob' => 1],
            'Unknown research subject' => ['Unknown principal investigator' => 1],
        ], $research['investigators']);
        $researchBudgets = $investigators->budgets($research['investigator_budgets']);
        $this->assertSame([['Computer science', 'Alice']], $researchBudgets['SEK']->get('labels'));
        $this->assertSame([201.0], $researchBudgets['SEK']->get('datasets.0.data'));
        $this->assertSame([['Information systems', 'Bob']], $researchBudgets['EUR']->get('labels'));
        $this->assertSame([100.5], $researchBudgets['EUR']->get('datasets.0.data'));
        $grantedBudgets = $investigators->budgets($stats->summary(2026, ['granted'], 'research_area')['investigator_budgets']);
        $this->assertSame(['EUR'], array_keys($grantedBudgets));
        $this->assertSame([100.5], $grantedBudgets['EUR']->get('datasets.0.data'));
        $researchChart = $investigators->grouped($research['investigators']);
        $this->assertSame([['Computer science', 'Alice'], ['Information systems', 'Bob'], ['Unknown research subject', 'Unknown principal investigator']], $researchChart->get('labels'));
        $this->assertSame([2, null, null], $researchChart->get('datasets.0.data'));
        $this->assertSame([null, 1, null], $researchChart->get('datasets.1.data'));
        $this->assertSame(['Information systems' => ['Bob' => 1]], $stats->summary(2026, ['granted'], 'research_area')['investigators']);

        $this->assertSame(['Computer science' => 2, 'Information systems' => 1, 'Unknown research subject' => 1], $research['counts']);
        $this->assertSame([
            'EUR' => ['Computer science' => 0.0, 'Information systems' => 100.5, 'Unknown research subject' => 0.0],
            'SEK' => ['Computer science' => 201.0, 'Information systems' => 0.0, 'Unknown research subject' => 0.0],
        ], $research['budgets']);
        $this->assertSame([
            'EUR' => ['Computer science' => 0.0, 'Information systems' => 25.75, 'Unknown research subject' => 0.0],
            'SEK' => ['Computer science' => 51.5, 'Information systems' => 0.0, 'Unknown research subject' => 0.0],
        ], $research['cofinancing']);
        $this->assertSame(['Computer science' => 2.5, 'Information systems' => 2.5, 'Unknown research subject' => 0.0], $research['phd_years']);
        $this->assertSame([
            'Agency A' => ['Computer science' => 2, 'Information systems' => 0, 'Unknown research subject' => 0],
            'Agency B' => ['Computer science' => 0, 'Information systems' => 1, 'Unknown research subject' => 0],
            'Unknown funding agency' => ['Computer science' => 0, 'Information systems' => 0, 'Unknown research subject' => 1],
        ], $research['agencies']);
        $this->assertSame(['Information systems' => 1], $stats->summary(2026, ['granted'], 'research_area')['counts']);
        $this->assertSame([
            'counts' => [], 'budgets' => [], 'agencies' => [], 'cofinancing' => [], 'phd_years' => [], 'investigators' => [], 'investigator_budgets' => [],
        ], $stats->summary(2024, ['sent', 'granted'], 'research_area'));

    }

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

    public function test_annual_totals_are_isolated_without_writing_the_shared_budget(): void
    {
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('pp');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('state');
        });

        foreach (['2025-12-31', '2026-01-01', '2026-12-31', '2027-01-01'] as $id => $deadline) {
            DB::table('project_proposals')->insert([
                'id' => (string) $id,
                'pp' => json_encode([
                    'submission_deadline' => $deadline,
                    'research_area' => 'Computer science',
                    'currency' => 'sek',
                    'budget_dsv' => 100,
                    'funding_organization' => 'Agency',
                    'budget_phd' => 1,
                ]),
            ]);
            DB::table('dashboards')->insert(['request_id' => (string) $id, 'state' => 'sent']);
        }

        // No budget table exists: annual calculations must remain read-only.
        $calculator = new ReCalcBudget();
        $budget = $calculator->scan(2026);
        $this->assertSame(2, $budget->preapproved_total);
        $this->assertSame(200, $budget->budget_dsv_total_sek);
        $this->assertSame(2, $budget->sent_total);
        $this->assertSame(['Agency' => 2], $budget->funding_org);
        $this->assertSame(2, $budget->research_area['Computer science']['phd']);
        $this->assertSame(100, $calculator->scan(2025)->budget_dsv_total_sek);
        $this->assertSame(0, $calculator->scan(2024)->preapproved_total);
    }
}
