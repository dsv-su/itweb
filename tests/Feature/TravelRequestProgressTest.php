<?php

namespace Tests\Feature;

use App\Models\TravelRequest;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TravelRequestProgressTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->afterBootstrapping(LoadConfiguration::class, function ($app) {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite.database', ':memory:');
            $app['config']->set('statamic.eloquent-driver.connection', 'sqlite');
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function test_progress_displays_reviewers_and_recorded_decisions(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Manager Example'],
            ['id' => 2, 'name' => 'Head Example'],
        ]);

        foreach (['approve' => 'Approved', 'deny' => 'Denied', 'return' => 'Returned'] as $decision => $label) {
            $tr = new TravelRequest;
            $tr->review_details = [
                'manager' => ['name' => 'Manager Example', 'decision' => 'approve', 'decided_at' => '2026-09-16T10:15:00+00:00'],
                'head' => ['name' => 'Head Example', 'decision' => $decision, 'decided_at' => '2026-09-17T11:30:00+00:00'],
            ];
            $html = view('requests.travel.progress', [
                'tr' => $tr,
                'dashboard' => (object) ['state' => 'head_'.['approve' => 'approved', 'deny' => 'denied', 'return' => 'returned'][$decision], 'manager_id' => 1, 'head_id' => 2],
            ])->render();

            $this->assertStringContainsString('Manager Example', $html);
            $this->assertStringContainsString('Head Example', $html);
            $this->assertStringContainsString(__($label), $html);
            $this->assertStringContainsString('2026-09-16', $html);
            $this->assertStringContainsString('2026-09-17', $html);
        }

        Schema::drop('users');
    }

    public function test_review_details_can_be_saved_without_a_comment(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });
        $migration = require database_path('migrations/2026_09_17_000000_add_review_details_to_travel_requests_table.php');
        $migration->up();

        $tr = new TravelRequest;
        $tr->review_details = ['manager' => [
            'user_id' => 1,
            'name' => 'Manager Example',
            'decision' => 'approve',
            'decided_at' => '2026-09-17T11:30:00+00:00',
        ]];
        $tr->save();

        $this->assertSame($tr->review_details, $tr->fresh()->review_details);
        $migration->down();
        $this->assertFalse(Schema::hasColumn('travel_requests', 'review_details'));
        Schema::drop('travel_requests');
    }

    public function test_legacy_requests_do_not_invent_decision_dates(): void
    {
        $html = view('requests.travel.progress', [
            'tr' => new TravelRequest,
            'dashboard' => (object) ['state' => 'manager_returned'],
        ])->render();

        $this->assertStringContainsString(__('Returned'), $html);
        $this->assertStringContainsString(__('Not assigned'), $html);
        $this->assertStringNotContainsString('<time', $html);
        $this->assertStringContainsString(__('Date unavailable'), $html);
        $this->assertStringContainsString('bg-amber-500', $html);
    }

    public function test_legacy_requests_show_latest_workflow_decision_timestamps(): void
    {
        Schema::create('workflow_signals', function (Blueprint $table) {
            $table->id();
            $table->integer('stored_workflow_id');
            $table->string('method');
            $table->timestamp('created_at');
        });
        DB::table('workflow_signals')->insert([
            ['stored_workflow_id' => 10, 'method' => 'manager_return', 'created_at' => '2026-09-14 09:00:00'],
            ['stored_workflow_id' => 10, 'method' => 'manager_approve', 'created_at' => '2026-09-15 10:15:00'],
            ['stored_workflow_id' => 10, 'method' => 'head_deny', 'created_at' => '2026-09-16 11:30:00'],
            ['stored_workflow_id' => 11, 'method' => 'head_approve', 'created_at' => '2026-09-17 12:00:00'],
        ]);
        $html = view('requests.travel.progress', [
            'tr' => new TravelRequest,
            'dashboard' => (object) ['state' => 'head_denied', 'workflow_id' => 10],
        ])->render();

        $this->assertStringContainsString('2026-09-15 10:15', $html);
        $this->assertStringContainsString('2026-09-16 11:30', $html);
        $this->assertStringNotContainsString('2026-09-14', $html);
        $this->assertStringNotContainsString('2026-09-17', $html);
        $this->assertStringContainsString(__('Approved'), $html);
        $this->assertStringContainsString(__('Denied'), $html);
        Schema::drop('workflow_signals');
    }
}
