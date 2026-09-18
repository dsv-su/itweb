<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Queue;
use App\Http\Middleware\DSVStaffEntitlement;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompletedTravelRequestEditTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        $this->withoutMiddleware([
            DSVStaffEntitlement::class,
            \Statamic\Http\Middleware\RedirectIfTwoFactorSetupIncomplete::class,
        ]);
        Schema::create('role_user', function (Blueprint $table) {
            $table->string('user_id');
            $table->string('role_id');
        });
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->string('id')->primary();
            foreach (['name', 'purpose', 'country', 'state', 'departure', 'return'] as $field) {
                $table->string($field)->nullable();
            }
            $table->timestamps();
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'name', 'type', 'state', 'user_id', 'manager_id', 'head_id', 'fo_id', 'workflow_id'] as $field) {
                $table->string($field)->nullable();
            }
            $table->timestamps();
        });
        DB::table('travel_requests')->insert(['id' => '01994111-1111-7111-8111-111111111111', 'state' => 'fo_approved', 'name' => 'Original']);
        DB::table('dashboards')->insert([
            'request_id' => '01994111-1111-7111-8111-111111111111', 'type' => 'travelrequest', 'state' => 'fo_approved',
            'user_id' => 'owner', 'fo_id' => 'officer', 'workflow_id' => 'original-workflow',
        ]);
        DB::table('role_user')->insert(['user_id' => 'officer', 'role_id' => 'financial_officer']);
    }

    private function loginAs(string $id): void
    {
        $user = new User;
        $user->id = $id;
        $this->actingAs($user);
    }

    public function test_only_fo_can_access_completed_edit_endpoints(): void
    {
        $this->loginAs('owner');
        $this->get('/travel/completed/01994111-1111-7111-8111-111111111111/edit')->assertForbidden();
        $this->post('/travel/completed/01994111-1111-7111-8111-111111111111')->assertForbidden();

        $this->loginAs('officer');
        DB::table('dashboards')->update(['state' => 'submitted']);
        $this->get('/travel/completed/01994111-1111-7111-8111-111111111111/edit')->assertForbidden();
        $this->post('/travel/completed/01994111-1111-7111-8111-111111111111')->assertForbidden();
    }

    public function test_save_preserves_completion_owner_and_workflow(): void
    {
        Queue::fake();
        $this->loginAs('officer');
        $this->post('/travel/completed/01994111-1111-7111-8111-111111111111', [
            'name' => 'Corrected', 'purpose' => 'Conference', 'countrytype' => 'domestic',
            'project_leader' => 'manager', 'unit_head' => 'head',
            'state' => 'submitted', 'user_id' => 'officer',
        ])->assertRedirect(route('fo-request-show', '01994111-1111-7111-8111-111111111111'));

        $this->assertDatabaseHas('travel_requests', [
            'id' => '01994111-1111-7111-8111-111111111111', 'name' => 'Corrected', 'state' => 'fo_approved', 'country' => 'Sverige',
        ]);
        $this->assertDatabaseHas('dashboards', [
            'request_id' => '01994111-1111-7111-8111-111111111111', 'name' => 'Corrected', 'state' => 'fo_approved',
            'user_id' => 'owner', 'workflow_id' => 'original-workflow', 'fo_id' => 'officer',
        ]);
        Queue::assertNothingPushed();
        $this->post('/travel', ['id' => '01994111-1111-7111-8111-111111111111'])->assertForbidden();
    }
}
