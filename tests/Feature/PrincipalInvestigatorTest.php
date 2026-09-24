<?php

namespace Tests\Feature;

use App\Livewire\Pp\PrincipalInvestigatorSearch;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Services\Directory\SearchPresenters;
use App\Services\Proposal\PrincipalInvestigator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class PrincipalInvestigatorTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->afterBootstrapping(LoadConfiguration::class, function ($app) {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite.database', ':memory:');
            $app['config']->set('statamic.eloquent-driver.connection', 'sqlite');
        });
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('super')->default(false);
            $table->timestamps();
        });
        foreach (['role_user' => 'role_id', 'group_user' => 'group_id'] as $name => $column) {
            Schema::create($name, function (Blueprint $table) use ($column) {
                $table->string('user_id');
                $table->string($column);
            });
        }
        (require database_path('migrations/2024_10_08_113244_create_project_proposals_table.php'))->up();
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id')->nullable();
            foreach (['request_id', 'name', 'status', 'type', 'user_id', 'fo_id', 'vice_id', 'unit_heads', 'unit_head_approved'] as $column) {
                $table->text($column)->nullable();
            }
            $table->string('state')->default('pending');
            $table->integer('created')->nullable();
            $table->boolean('multiple_heads')->default(false);
            $table->timestamps();
        });
        foreach (['settings_fos', 'settings_fo_eus'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->boolean('active');
            });
        }
        DB::table('role_user')->insert(['user_id' => 'vice', 'role_id' => 'vice_head']);
        $this->withoutMiddleware();
    }

    private function actor(?string $role = null): User
    {
        $actor = User::create(['name' => 'Editor', 'email' => uniqid().'@su.se']);
        if ($role === 'super') {
            $actor->forceFill(['super' => true])->save();
        } elseif ($role) {
            DB::table('role_user')->insert(['user_id' => $actor->id, 'role_id' => $role]);
        }
        $this->actingAs($actor);

        return $actor;
    }

    private function proposal(User $owner): ProjectProposal
    {
        return ProjectProposal::create([
            'user_id' => $owner->id, 'name' => 'Test', 'created' => time(),
            'status_stage1' => 'pending', 'status_stage2' => 'pending', 'status_stage3' => 'pending',
            'pp' => ['principal_investigator' => $owner->name, 'principal_investigator_email' => $owner->email],
            'files' => [],
        ]);
    }

    private function payload(ProjectProposal $proposal, string $type = 'save'): array
    {
        return [
            'id' => $proposal->id, 'type' => $type, 'title' => 'Test', 'objective' => 'Research',
            'principal_investigator' => 'Forged name', 'principal_investigator_email' => 'forged@example.com',
            'principal_investigator_uid' => 'researcher', 'unit_head' => ['head'],
        ];
    }

    private function directory(): void
    {
        $this->partialMock(PrincipalInvestigator::class, function ($mock) {
            $mock->shouldReceive('directoryProfile')->with('researcher')->andReturn([
                'uid' => 'researcher', 'name' => 'Researcher', 'email' => 'researcher@su.se',
            ]);
        });
    }

    public function test_privileged_roles_can_save_on_behalf_of_a_new_directory_user(): void
    {
        $this->directory();
        foreach (['super', 'vice_head', 'site_administrator'] as $role) {
            $actor = $this->actor($role);
            $proposal = $this->proposal($actor);
            $this->post(route('pp.submit'), $this->payload($proposal))->assertRedirect()->assertSessionHasNoErrors();
            $owner = User::where('email', 'researcher@su.se')->firstOrFail();
            $this->assertSame($owner->id, $proposal->fresh()->user_id);
            $this->assertSame($owner->id, $proposal->fresh()->dashboard->user_id);
            $this->assertSame('Researcher', $proposal->fresh()->pp['principal_investigator']);
            $this->assertSame('researcher@su.se', $proposal->fresh()->pp['principal_investigator_email']);
            $this->assertDatabaseHas('group_user', ['user_id' => $owner->id, 'group_id' => 'projektledare']);
        }
        $this->assertSame(1, User::where('email', 'researcher@su.se')->count());
    }

    public function test_ordinary_owner_cannot_submit_a_directory_selection(): void
    {
        $owner = $this->actor();
        $proposal = $this->proposal($owner);
        $this->post(route('pp.submit'), $this->payload($proposal))->assertForbidden();
        $this->assertSame($owner->id, $proposal->fresh()->user_id);
    }

    public function test_new_submission_and_resume_start_workflows_with_the_selected_owner(): void
    {
        foreach (glob(database_path('migrations/*create_workflow*table.php')) as $migration) {
            require_once $migration;
            $class = \Illuminate\Support\Str::studly(substr(basename($migration, '.php'), 18));
            (new $class)->up();
        }
        Queue::fake();
        $this->directory();
        $actor = $this->actor('super');
        $proposal = $this->proposal($actor);
        $this->post(route('pp.submit'), $this->payload($proposal, 'preapproval'))
            ->assertRedirect()->assertSessionHasNoErrors();
        $owner = User::where('email', 'researcher@su.se')->firstOrFail();
        $dashboard = $proposal->fresh()->dashboard;
        $this->assertSame($owner->id, $dashboard->user_id);
        $this->assertNotNull($dashboard->workflow_id);
        $workflowId = $dashboard->workflow_id;
        $dashboard->state = 'head_returned';
        $dashboard->save();
        $this->post(route('pp.submit'), $this->payload($proposal, 'resume'))
            ->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame($owner->id, $proposal->fresh()->dashboard->user_id);
        $this->assertNotSame($workflowId, $proposal->fresh()->dashboard->workflow_id);
        $this->assertStringContainsString('Editor', $proposal->fresh()->pp['user_comments']);
    }

    public function test_unrelated_user_cannot_take_over_a_proposal(): void
    {
        $proposal = $this->proposal($this->actor());
        $this->actor();
        $payload = $this->payload($proposal);
        unset($payload['principal_investigator_uid']);
        $this->post(route('pp.submit'), $payload)->assertForbidden();
    }

    public function test_invalid_directory_selection_does_not_change_ownership(): void
    {
        $actor = $this->actor('super');
        $proposal = $this->proposal($actor);
        $this->partialMock(PrincipalInvestigator::class, function ($mock) {
            $mock->shouldReceive('directoryProfile')->andThrow(
                \Illuminate\Validation\ValidationException::withMessages(['principal_investigator_uid' => 'Invalid investigator'])
            );
        });
        $this->post(route('pp.submit'), $this->payload($proposal))
            ->assertSessionHasErrors('principal_investigator_uid');
        $this->assertSame($actor->id, $proposal->fresh()->user_id);
        $this->assertDatabaseCount('dashboards', 0);
    }

    public function test_edit_and_complete_keep_both_owners_and_ignore_forged_display_fields(): void
    {
        $owner = $this->actor();
        $proposal = $this->proposal($owner);
        $payload = $this->payload($proposal);
        unset($payload['principal_investigator_uid']);
        $this->post(route('pp.submit'), $payload)->assertRedirect();
        $this->actor('super');
        foreach (['edit', 'complete'] as $type) {
            $this->post(route('pp.submit'), array_replace($payload, ['type' => $type]))->assertRedirect()->assertSessionHasNoErrors();
            $this->assertSame($owner->id, $proposal->fresh()->user_id);
            $this->assertSame($owner->id, $proposal->fresh()->dashboard->user_id);
            $this->assertSame($owner->email, $proposal->fresh()->pp['principal_investigator_email']);
        }
    }

    public function test_edit_and_complete_can_replace_the_investigator(): void
    {
        $this->directory();
        $actor = $this->actor('super');
        foreach (['edit', 'complete'] as $type) {
            $proposal = $this->proposal($actor);
            $payload = $this->payload($proposal);
            unset($payload['principal_investigator_uid']);
            $this->post(route('pp.submit'), $payload)->assertRedirect();
            $this->post(route('pp.submit'), $this->payload($proposal, $type))->assertRedirect()->assertSessionHasNoErrors();
            $owner = User::where('email', 'researcher@su.se')->firstOrFail();
            $this->assertSame($owner->id, $proposal->fresh()->user_id);
            $this->assertSame($owner->id, $proposal->fresh()->dashboard->user_id);
        }
    }

    public function test_search_and_selection_use_the_directory(): void
    {
        $this->actor('super');
        $this->directory();
        $this->mock(SearchPresenters::class, function ($mock) {
            $mock->shouldReceive('execute')->with('Research')->once()->andReturn([
                (object) ['uid' => 'researcher', 'name' => 'Researcher', 'email' => 'researcher@su.se'],
            ]);
        });
        Livewire::test(PrincipalInvestigatorSearch::class)
            ->set('search', 'Research')->assertSee('researcher@su.se')
            ->call('select', 'researcher')->assertDispatched('principal-investigator-selected', uid: 'researcher', name: 'Researcher', email: 'researcher@su.se');
    }

    public function test_search_is_restricted(): void
    {
        $this->actor();
        Livewire::test(PrincipalInvestigatorSearch::class)->assertForbidden();
    }
}
