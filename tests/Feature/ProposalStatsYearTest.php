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
                'pp' => json_encode(['submission_deadline' => $deadline, 'unit_head' => $heads]),
            ]);
            DB::table('dashboards')->insert(['request_id' => (string) $id, 'state' => $state]);
        }
        // Duplicate dashboard entries must not duplicate a proposal's contribution.
        DB::table('dashboards')->insert(['request_id' => '0', 'state' => 'sent']);
        $stats = new \App\Services\Budget\ProposalUnitStats();
        $this->assertSame(['Alpha' => 2, 'Beta' => 1, 'Unknown unit' => 2], $stats->counts(2026, ['sent', 'granted']));
        $this->assertSame(['Alpha' => 1], $stats->counts(2026, ['granted']));
        $this->assertSame([], $stats->counts(2024, ['sent', 'granted']));
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
