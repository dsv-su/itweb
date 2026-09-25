<?php

namespace Tests\Feature;

use App\Bus\Middleware\SkipEndedProposalJobs;
use App\Http\Controllers\AdminController;
use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Services\Proposal\AdminProposalWorkflow;
use App\Workflows\DSVProjectPWorkflow;
use App\Workflows\Transitions\StateUpdateTransition;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Workflow\Middleware\WithoutOverlappingMiddleware;
use Workflow\Models\StoredWorkflow;
use Tests\TestCase;
use Workflow\States\WorkflowFailedStatus;

class AdminProposalWorkflowTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->instance('routes.cached', false);
        $app->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function () use ($app) {
            $app->instance('env', 'testing');
            $app['config']->set('session.driver', 'array');
            $app['config']->set('cache.default', 'array');
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
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status_stage1');
            $table->string('status_stage2');
            $table->string('status_stage3');
            $table->json('pp');
            $table->json('files')->nullable();
            $table->string('last_reminder_type')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->boolean('reminder')->default(true);
            $table->timestamps();
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id');
            $table->string('type');
            $table->string('state');
            $table->string('status')->nullable();
            $table->json('unit_heads')->nullable();
            $table->json('unit_head_approved')->nullable();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->timestamps();
        });
        foreach (glob(base_path('vendor/laravel-workflow/laravel-workflow/src/migrations/*.php')) as $file) {
            require_once $file;
        }
        (new \CreateWorkflowsTable)->up();
        (new \CreateWorkflowExceptionsTable)->up();
        (new \CreateWorkflowRelationshipsTable)->up();
    }

    private function proposal(string $workflowStatus = 'waiting'): array
    {
        $workflow = StoredWorkflow::create(['class' => DSVProjectPWorkflow::class, 'status' => $workflowStatus]);
        $proposal = ProjectProposal::create([
            'status_stage1' => 'head_approved', 'status_stage2' => 'uploaded', 'status_stage3' => 'submitted',
            'pp' => ['funding_organization' => 'Council'],
        ]);
        $dashboard = Dashboard::create(['request_id' => $proposal->id, 'state' => 'head_approved', 'type' => 'projectproposal']);
        $dashboard->workflow_id = $workflow->id;
        $dashboard->save();

        return [$proposal, $dashboard, $workflow];
    }

    public function test_ending_is_idempotent_and_preserves_state_and_workflow_history(): void
    {
        [$proposal, $dashboard, $workflow] = $this->proposal();
        $service = new AdminProposalWorkflow;
        $service->end($proposal, 'admin-1');
        $service->end($proposal, 'admin-1');
        $this->assertSame(WorkflowFailedStatus::class, $workflow->fresh()->status::class);
        $this->assertSame('head_approved', (string) $dashboard->fresh()->state);
        $this->assertSame('head_approved', $proposal->fresh()->status_stage1);
        $this->assertFalse((bool) $proposal->fresh()->reminder);
        $this->assertSame($workflow->id, $dashboard->fresh()->workflow_id);
        $this->assertSame(1, $workflow->exceptions()->count());
        $this->assertStringContainsString('admin-1', $workflow->exceptions()->first()->exception);
    }

    public function test_all_proposal_states_can_be_set_and_metadata_is_preserved(): void
    {
        foreach (array_keys(AdminProposalWorkflow::STATES) as $state) {
            [$proposal, $dashboard, $workflow] = $this->proposal();
            (new AdminProposalWorkflow)->end($proposal, 'admin-1', $state);
            $this->assertSame($state, (string) $dashboard->fresh()->state);
            $this->assertSame($state, $proposal->fresh()->status_stage1);
            $this->assertSame($state, $proposal->fresh()->pp['status']);
            $this->assertSame('Council', $proposal->fresh()->pp['funding_organization']);
            $this->assertSame('submitted', $proposal->fresh()->status_stage3);
            $this->assertSame('failed', (string) $workflow->fresh()->status);
        }
    }

    public function test_invalid_state_leaves_workflow_and_proposal_unchanged(): void
    {
        [$proposal, $dashboard, $workflow] = $this->proposal();
        try {
            (new AdminProposalWorkflow)->end($proposal, 'admin-1', 'manager_approved');
            $this->fail('Travel-only states must be rejected.');
        } catch (ValidationException) {
            $this->assertSame('waiting', (string) $workflow->fresh()->status);
            $this->assertTrue((bool) $proposal->fresh()->reminder);
            $this->assertSame('head_approved', (string) $dashboard->fresh()->state);
        }
    }

    public function test_created_and_completed_workflows_are_handled(): void
    {
        foreach (['created' => 'failed', 'pending' => 'failed', 'completed' => 'completed', 'failed' => 'failed'] as $initial => $expected) {
            [$proposal, , $workflow] = $this->proposal($initial);
            (new AdminProposalWorkflow)->end($proposal, 'admin-1', 'sent');
            $this->assertSame($expected, (string) $workflow->fresh()->status);
            $this->assertSame('final_approved', $proposal->fresh()->status_stage2);
        }
    }

    public function test_busy_workflows_are_not_changed(): void
    {
        [$proposal, , $workflow] = $this->proposal();
        $lock = new WithoutOverlappingMiddleware($workflow->id, WithoutOverlappingMiddleware::ACTIVITY, 0, 60);
        $job = new \stdClass;
        $this->assertTrue($lock->lock($job));
        try {
            (new AdminProposalWorkflow)->end($proposal, 'admin-1', 'granted');
            $this->fail('An active activity must block the override.');
        } catch (ValidationException) {
            $this->assertSame('waiting', (string) $workflow->fresh()->status);
            $this->assertSame('head_approved', $proposal->fresh()->status_stage1);
        } finally {
            $lock->unlock($job);
        }
    }

    public function test_queued_activities_for_ended_proposals_are_skipped(): void
    {
        [$proposal, , $workflow] = $this->proposal();
        $activity = new StateUpdateTransition(0, now(), $workflow);
        $middleware = new SkipEndedProposalJobs;
        $this->assertSame('executed', $middleware->handle($activity, fn () => 'executed'));
        (new AdminProposalWorkflow)->end($proposal, 'admin-1');
        $this->assertNull($middleware->handle($activity, fn () => $this->fail('An ended workflow must not execute activities.')));
        $this->assertSame('unrelated', $middleware->handle(new \stdClass, fn () => 'unrelated'));
    }

    public function test_queued_reminders_are_skipped_when_disabled(): void
    {
        [$proposal] = $this->proposal();
        $job = new \App\Jobs\ReminderEmails\SendUHReminderEmail($proposal->id);
        $middleware = new SkipEndedProposalJobs;
        $this->assertSame('sent', $middleware->handle($job, fn () => 'sent'));
        (new AdminProposalWorkflow)->end($proposal, 'admin-1');
        $this->assertNull($middleware->handle($job, fn () => $this->fail('Disabled reminders must not send.')));
    }

    public function test_non_helpdesk_users_cannot_use_either_action(): void
    {
        [$proposal, , $workflow] = $this->proposal();
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 'unauthorized-user';
        $user->shouldReceive('isHelpdesk')->andReturn(false);
        $this->actingAs($user)->withoutMiddleware(\App\Http\Middleware\DSVStaffEntitlement::class);
        $this->post(route('admin.pp.end-workflow', $proposal))->assertForbidden();
        $this->post(route('admin.pp.resume-workflow', $proposal), ['resume_stage' => 'fo_returned', 'workflow_id' => $workflow->id])->assertForbidden();
        $this->patch(route('admin.pp.state', $proposal), ['state' => 'granted'])->assertForbidden();
        $this->assertSame('waiting', (string) $workflow->fresh()->status);
    }

    public function test_missing_dashboard_or_workflow_does_not_partially_update_proposal(): void
    {
        [$proposal, $dashboard] = $this->proposal();
        $dashboard->workflow_id = 999;
        $dashboard->save();
        try {
            (new AdminProposalWorkflow)->end($proposal, 'admin-1', 'granted');
            $this->fail('A broken workflow link must be reported.');
        } catch (ValidationException) {
            $this->assertSame('head_approved', (string) $dashboard->fresh()->state);
            $this->assertTrue((bool) $proposal->fresh()->reminder);
        }
        $dashboard->delete();
        $this->expectException(ValidationException::class);
        (new AdminProposalWorkflow)->end($proposal, 'admin-1', 'granted');
    }

    public function test_helpdesk_can_use_both_actions_and_invalid_http_state_is_rejected(): void
    {
        [$proposal, $dashboard, $workflow] = $this->proposal();
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 'helpdesk-user';
        $user->shouldReceive('isHelpdesk')->andReturn(true);
        $this->actingAs($user)->withoutMiddleware(\App\Http\Middleware\DSVStaffEntitlement::class);
        $this->from('/admin?search=Council')->patch(route('admin.pp.state', $proposal), ['state' => 'manager_approved'])
            ->assertRedirect('/admin?search=Council')->assertSessionHasErrors('state');
        $this->assertSame('waiting', (string) $workflow->fresh()->status);
        $this->from('/admin?search=Council')->post(route('admin.pp.end-workflow', $proposal))
            ->assertRedirect('/admin?search=Council')->assertSessionHas('success');
        $this->assertSame('failed', (string) $workflow->fresh()->status);
        $this->from('/admin?search=Council')->patch(route('admin.pp.state', $proposal), ['state' => 'granted'])
            ->assertRedirect('/admin?search=Council')->assertSessionHas('success');
        $this->assertSame('granted', (string) $dashboard->fresh()->state);
    }

    public function test_each_resume_stage_creates_and_starts_its_workflow_and_restores_reminders(): void
    {
        Queue::fake();
        foreach (AdminProposalWorkflow::RESUME_WORKFLOWS as $stage => $class) {
            [$proposal, $dashboard, $oldWorkflow] = $this->proposal();
            $proposal->forceFill([
                'reminder' => false,
                'last_reminder_type' => 'old-reminder',
                'last_reminder_sent_at' => now(),
                'files' => [['type' => 'draft'], ['type' => 'budget']],
            ])->save();
            $dashboard->forceFill(['unit_heads' => ['head-1'], 'unit_head_approved' => '{"head-1":1}'])->save();
            (new AdminProposalWorkflow)->resume($proposal, 'admin-1', $stage, $oldWorkflow->id);

            $dashboard->refresh();
            $proposal->refresh();
            $replacement = StoredWorkflow::findOrFail($dashboard->workflow_id);
            $this->assertNotSame($oldWorkflow->id, $replacement->id);
            $this->assertSame('failed', (string) $oldWorkflow->fresh()->status);
            $this->assertSame($class, $replacement->class);
            $this->assertSame('pending', (string) $replacement->status);
            $this->assertSame('submitted', (string) $dashboard->state);
            $this->assertSame('resumed', $dashboard->status);
            $this->assertTrue((bool) $proposal->reminder);
            $this->assertNull($proposal->last_reminder_type);
            $this->assertNull($proposal->last_reminder_sent_at);
            $this->assertSame('submitted', $proposal->status_stage1);
            $this->assertSame('uploaded', $proposal->status_stage2);
            $this->assertSame('resumed', $proposal->pp['status']);
            $this->assertSame('Council', $proposal->pp['funding_organization']);
            $this->assertSame($stage === 'head_returned' ? 0 : 1, json_decode($dashboard->unit_head_approved, true)['head-1']);
            Queue::assertPushed($class, fn ($job) => $job->storedWorkflow->id === $replacement->id && $job->afterCommit === true);
            $this->assertSame($dashboard->id, $replacement->workflowArguments()[0]->id);
        }
    }

    public function test_repeated_resume_submission_is_rejected_without_replacing_new_workflow(): void
    {
        Queue::fake();
        [$proposal, $dashboard, $oldWorkflow] = $this->proposal();
        $service = new AdminProposalWorkflow;
        $service->resume($proposal, 'admin-1', 'fo_returned', $oldWorkflow->id);
        $newId = $dashboard->fresh()->workflow_id;
        try {
            $service->resume($proposal, 'admin-1', 'fo_returned', $oldWorkflow->id);
            $this->fail('A stale form must not restart the replacement workflow.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('resume_stage', $exception->errors());
            $this->assertSame($newId, $dashboard->fresh()->workflow_id);
            $this->assertSame(2, StoredWorkflow::count());
            $this->assertSame('pending', (string) StoredWorkflow::findOrFail($newId)->status);
        }
    }

    public function test_resume_rejects_invalid_stage_and_busy_workflows_without_changes(): void
    {
        Queue::fake();
        [$proposal, $dashboard, $oldWorkflow] = $this->proposal();
        $service = new AdminProposalWorkflow;
        try {
            $service->resume($proposal, 'admin-1', 'manager_returned', $oldWorkflow->id);
            $this->fail('Travel resume stages must not be accepted.');
        } catch (ValidationException) {
            $this->assertSame('waiting', (string) $oldWorkflow->fresh()->status);
        }
        $lock = new WithoutOverlappingMiddleware($oldWorkflow->id, WithoutOverlappingMiddleware::ACTIVITY, 0, 60);
        $job = new \stdClass;
        $this->assertTrue($lock->lock($job));
        try {
            $service->resume($proposal, 'admin-1', 'head_returned', $oldWorkflow->id);
            $this->fail('An active activity must block resumption.');
        } catch (ValidationException) {
            $this->assertSame($oldWorkflow->id, $dashboard->fresh()->workflow_id);
            $this->assertSame(1, StoredWorkflow::count());
            Queue::assertNothingPushed();
        } finally {
            $lock->unlock($job);
        }
    }

    public function test_helpdesk_resume_endpoint_preserves_search_and_handles_missing_files(): void
    {
        Queue::fake();
        [$proposal, $dashboard, $oldWorkflow] = $this->proposal('completed');
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 'helpdesk-user';
        $user->shouldReceive('isHelpdesk')->andReturn(true);
        $this->actingAs($user)->withoutMiddleware(\App\Http\Middleware\DSVStaffEntitlement::class);
        $this->from('/admin?search=Council')->post(route('admin.pp.resume-workflow', $proposal), [
            'resume_stage' => 'final_returned', 'workflow_id' => $oldWorkflow->id,
        ])->assertRedirect('/admin?search=Council')->assertSessionHas('success');
        $this->assertSame('completed', (string) $oldWorkflow->fresh()->status);
        $this->assertNotSame($oldWorkflow->id, $dashboard->fresh()->workflow_id);
        $this->assertSame('pending', $proposal->fresh()->status_stage2);
    }

    public function test_failed_resume_start_rolls_back_the_replacement_and_old_workflow_termination(): void
    {
        Queue::fake();
        [$proposal, $dashboard, $oldWorkflow] = $this->proposal();
        \Illuminate\Support\Facades\Event::listen(\Workflow\Events\WorkflowStarted::class, function () {
            throw new \RuntimeException('Simulated startup failure');
        });
        try {
            (new AdminProposalWorkflow)->resume($proposal, 'admin-1', 'head_returned', $oldWorkflow->id);
            $this->fail('Startup should fail.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated startup failure', $exception->getMessage());
            $this->assertSame($oldWorkflow->id, $dashboard->fresh()->workflow_id);
            $this->assertSame('head_approved', (string) $dashboard->fresh()->state);
            $this->assertSame('waiting', (string) $oldWorkflow->fresh()->status);
            $this->assertSame(1, StoredWorkflow::count());
            $this->assertSame(0, $oldWorkflow->exceptions()->count());
            $this->assertTrue((bool) $proposal->fresh()->reminder);
            Queue::assertNothingPushed();
        }
    }

    public function test_actions_require_helpdesk_and_validate_state(): void
    {
        $controller = new AdminController;
        $this->assertContains('helpdesk', array_column($controller->getMiddleware(), 'middleware'));
        [$proposal] = $this->proposal();
        $request = Request::create('/admin', 'PATCH', ['state' => 'invalid']);
        $request->setUserResolver(fn () => new User(['id' => 1]));
        $this->expectException(ValidationException::class);
        $controller->setProposalState($request, $proposal, new AdminProposalWorkflow);
    }
}
