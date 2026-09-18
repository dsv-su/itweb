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

class TravelRequestFoSwitchTest extends TestCase
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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
        });
        foreach (['role_user' => 'role_id', 'group_user' => 'group_id'] as $name => $column) {
            Schema::create($name, function (Blueprint $table) use ($column) {
                $table->string('user_id');
                $table->string($column);
            });
        }
        Schema::create('travel_requests', fn (Blueprint $table) => $table->string('id')->primary());
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'fo_id', 'state'] as $field) {
                $table->string($field);
            }
            $table->timestamps();
        });
        Schema::create('fo_comments', function (Blueprint $table) {
            $table->id();
            $table->string('reqid');
            $table->string('user_id');
            $table->text('comment');
            $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 'old', 'name' => 'Previous Officer'],
            ['id' => 'new', 'name' => 'New Officer'],
            ['id' => 'outsider', 'name' => 'Other User'],
        ]);
        DB::table('role_user')->insert(['user_id' => 'old', 'role_id' => 'financial_officer']);
        DB::table('group_user')->insert(['user_id' => 'new', 'group_id' => 'ekonomi']);
        DB::table('travel_requests')->insert(['id' => 'trip']);
        DB::table('dashboards')->insert(['id' => 1, 'request_id' => 'trip', 'type' => 'travelrequest', 'fo_id' => 'old', 'state' => 'fo_approved']);
        $this->actingAs(User::find('old'));
    }

    public function test_switch_updates_assignment_and_records_actor_without_changing_state(): void
    {
        $component = new \App\Livewire\RequestSearch;
        $component->switchFo(1);
        $component->selectedFoId = 'new';
        $component->saveFo();
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'new', 'state' => 'fo_approved']);
        $this->assertDatabaseHas('fo_comments', ['reqid' => 'trip', 'user_id' => 'old']);
        $comment = DB::table('fo_comments')->value('comment');
        $this->assertStringContainsString('Previous Officer', $comment);
        $this->assertStringContainsString('New Officer', $comment);
        $component->switchFo(1);
        $component->saveFo();
        $this->assertDatabaseCount('fo_comments', 1);
    }

    public function test_selection_must_belong_to_ekonomi(): void
    {
        $component = new \App\Livewire\RequestSearch;
        $component->switchFo(1);
        $component->selectedFoId = 'outsider';
        try {
            $component->saveFo();
            $this->fail('Invalid officer was accepted.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('selectedFoId', $e->errors());
        }
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'old']);
        $this->assertDatabaseCount('fo_comments', 0);
    }

    public function test_non_fo_cannot_save_even_with_a_forged_selection(): void
    {
        $this->actingAs(User::find('outsider'));
        $component = new \App\Livewire\RequestSearch;
        $component->switchingFoId = 1;
        $component->selectedFoId = 'new';
        try {
            $component->saveFo();
            $this->fail('Unauthorized switch was accepted.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'old']);
        $this->assertDatabaseCount('fo_comments', 0);
    }
}
