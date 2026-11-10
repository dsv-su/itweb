<?php

namespace Tests\Feature;

use App\Livewire\Pp\DeleteProposal;
use App\Mail\ProposalDeleted;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProposalDeletionTest extends \Tests\TestCase
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
        $this->travelTo(now()->startOfSecond());
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->boolean('super')->default(false);
        });
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('name');
            $table->string('status_stage3');
            $table->timestamps();
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('type');
            $table->string('state');
            $table->integer('workflow_id')->nullable();
            $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 'pi', 'name' => 'Investigator', 'email' => 'pi@example.test', 'super' => false],
            ['id' => 'other', 'name' => 'Other', 'email' => 'other@example.test', 'super' => false],
            ['id' => 'admin', 'name' => 'Admin', 'email' => 'admin@example.test', 'super' => true],
        ]);
        DB::table('project_proposals')->insert([
            'id' => 'proposal', 'user_id' => 'pi', 'name' => 'Draft <title>', 'status_stage3' => 'saved',
            'created_at' => now()->subWeeks(4), 'updated_at' => now()->subWeeks(4),
        ]);
        DB::table('dashboards')->insert([
            'request_id' => 'proposal', 'type' => 'projectproposal', 'state' => 'pending',
            'created_at' => now()->subWeeks(4), 'updated_at' => now()->subWeeks(4),
        ]);
        $this->actingAs(User::findOrFail('pi'));
        Mail::fake();
        Storage::fake();
    }

    public function test_only_pi_and_superuser_can_delete(): void
    {
        $this->assertTrue(ProjectProposal::findOrFail('proposal')->allowDelete());
        $this->actingAs(User::findOrFail('other'));
        $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
        $this->actingAs(User::findOrFail('admin'));
        $this->assertTrue(ProjectProposal::findOrFail('proposal')->allowDelete());
    }

    public function test_age_and_both_update_timestamps_must_be_at_least_four_weeks_old(): void
    {
        foreach (['created_at', 'updated_at'] as $column) {
            DB::table('project_proposals')->update([$column => now()->subDays(27)]);
            $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
            DB::table('project_proposals')->update([$column => now()->subWeeks(4)]);
        }
        DB::table('dashboards')->update(['updated_at' => now()->subDay()]);
        $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
    }

    public function test_started_workflows_and_submitted_proposals_cannot_be_deleted_even_by_superuser(): void
    {
        $this->actingAs(User::findOrFail('admin'));
        DB::table('dashboards')->update(['workflow_id' => 123]);
        $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
        DB::table('dashboards')->update(['workflow_id' => null, 'state' => 'submitted']);
        $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
        DB::table('dashboards')->update(['state' => 'pending']);
        DB::table('project_proposals')->update(['status_stage3' => 'submitted']);
        $this->assertFalse(ProjectProposal::findOrFail('proposal')->allowDelete());
    }

    public function test_delete_removes_records_and_files_and_emails_pi_when_admin_deletes(): void
    {
        $this->actingAs(User::findOrFail('admin'));
        Storage::put('proposals/proposal/draft/test.pdf', 'test');
        $component = new DeleteProposal;
        $component->proposalId = 'proposal';
        $component->delete();
        $this->assertDatabaseMissing('project_proposals', ['id' => 'proposal']);
        $this->assertDatabaseMissing('dashboards', ['request_id' => 'proposal']);
        Storage::assertMissing('proposals/proposal/draft/test.pdf');
        Mail::assertSent(ProposalDeleted::class, fn ($mail) => $mail->hasTo('pi@example.test') && $mail->proposalId === 'proposal');
        $html = (new ProposalDeleted('Investigator', 'Draft <title>', 'proposal', '2026-10-27'))->render();
        $this->assertStringContainsString('Draft &lt;title&gt;', $html);
        $this->assertStringContainsString('This is an automated email', $html);
    }

    public function test_delete_rechecks_eligibility_and_sends_nothing_when_denied(): void
    {
        $component = new DeleteProposal;
        $component->proposalId = 'proposal';
        DB::table('dashboards')->update(['workflow_id' => 123]);
        try {
            $component->delete();
            $this->fail('Deletion should be denied.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        $this->assertDatabaseHas('project_proposals', ['id' => 'proposal']);
        Mail::assertNothingSent();
    }

    public function test_livewire_confirmation_and_delete_action(): void
    {
        \Livewire\Livewire::test(DeleteProposal::class, ['proposalId' => 'proposal'])
            ->assertSee('Delete')
            ->assertSeeHtml('wire:confirm=')
            ->call('delete')
            ->assertDispatched('pp-update');
        $this->assertDatabaseMissing('project_proposals', ['id' => 'proposal']);
    }

    public function test_unauthorized_livewire_delete_is_forbidden(): void
    {
        $this->actingAs(User::findOrFail('other'));
        \Livewire\Livewire::test(DeleteProposal::class, ['proposalId' => 'proposal'])
            ->call('delete')
            ->assertForbidden();
        $this->assertDatabaseHas('project_proposals', ['id' => 'proposal']);
        Mail::assertNothingSent();
    }

    public function test_deleting_a_draft_preserves_statistics_without_recalculating_budget(): void
    {
        Schema::table('project_proposals', fn (Blueprint $table) => $table->json('pp')->nullable());
        $payload = json_encode([
            'submission_deadline' => '2026-06-15', 'principal_investigator' => 'Investigator',
            'research_area' => 'Computer science', 'budget_dsv' => 500, 'budget_project' => 1000,
            'budget_phd' => 2, 'cofinancing_needed' => 100, 'currency' => 'sek',
            'funding_organization' => 'Agency',
        ]);
        DB::table('project_proposals')->update(['pp' => $payload]);
        foreach (['sent', 'granted', 'final_approved'] as $state) {
            DB::table('project_proposals')->insert([
                'id' => $state, 'user_id' => 'pi', 'name' => $state,
                'status_stage3' => 'submitted', 'pp' => $payload,
            ]);
            DB::table('dashboards')->insert([
                'request_id' => $state, 'type' => 'projectproposal', 'state' => $state,
                'workflow_id' => 123,
            ]);
        }
        $snapshot = function () {
            $stats = app(\App\Services\Budget\ProposalUnitStats::class);
            $investigators = app(\App\Services\Stats\PrincipalInvestigatorCharts::class);

            return [
                'monthly' => app(\App\Services\Stats\MonthlyProposalStats::class)->build(\Carbon\CarbonImmutable::parse('2026-06-01')),
                'annual' => (new \App\Services\Budget\ReCalcBudget)->scan(2026)->getAttributes(),
                'submitted' => $stats->summary(2026, ['sent', 'granted'], 'research_area'),
                'granted' => $stats->summary(2026, ['granted'], 'research_area'),
                'investigators' => $investigators->counts(2026, false),
                'granted_investigators' => $investigators->counts(2026, true),
            ];
        };
        $before = $snapshot();
        $this->assertSame(2, $before['monthly']['total']);
        $this->assertSame(3, $before['annual']['preapproved_total']);

        $this->mock(\App\Services\Budget\ReCalcBudget::class)->shouldNotReceive('scan');
        $queries = [];
        DB::listen(function ($query) use (&$queries) {
            $queries[] = strtolower(ltrim($query->sql));
        });
        $component = new DeleteProposal;
        $component->proposalId = 'proposal';
        $component->delete();
        foreach ($queries as $query) {
            $this->assertDoesNotMatchRegularExpression('/^(insert|update|delete).*dsv_budgets/', $query);
        }
        $this->assertSame($before, $snapshot());
    }

    private function addReminderColumns(): void
    {
        Schema::table('project_proposals', function (Blueprint $table) {
            $table->boolean('reminder')->default(true);
            $table->string('last_reminder_type')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
        });
    }

    public function test_inactive_reminder_command_preserves_deletion_eligibility_and_throttles_repeats(): void
    {
        $this->addReminderColumns();
        \Illuminate\Support\Facades\Queue::fake();
        $before = ProjectProposal::findOrFail('proposal')->updated_at;
        $this->artisan('send-proposal-reminders')->assertSuccessful();
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\ReminderEmails\SendInactiveProposalReminderEmail::class, 1);
        $proposal = ProjectProposal::findOrFail('proposal');
        $this->assertTrue($proposal->updated_at->equalTo($before));
        $this->assertTrue($proposal->allowDelete());
        $this->assertSame('REMIND_USER_INACTIVE_PROPOSAL', $proposal->last_reminder_type);
        $this->artisan('send-proposal-reminders')->assertSuccessful();
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\ReminderEmails\SendInactiveProposalReminderEmail::class, 1);
        $this->travel(3)->days();
        $this->artisan('send-proposal-reminders')->assertSuccessful();
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\ReminderEmails\SendInactiveProposalReminderEmail::class, 2);
    }

    public function test_inactive_reminder_dry_run_and_ineligible_drafts_do_not_dispatch(): void
    {
        $this->addReminderColumns();
        \Illuminate\Support\Facades\Queue::fake();
        $this->artisan('send-proposal-reminders', ['--dry-run' => true])->assertSuccessful();
        $this->assertNull(ProjectProposal::findOrFail('proposal')->last_reminder_sent_at);
        foreach ([['reminder' => false], ['reminder' => true, 'updated_at' => now()]] as $changes) {
            DB::table('project_proposals')->update($changes);
            $this->artisan('send-proposal-reminders')->assertSuccessful();
        }
        DB::table('project_proposals')->update(['updated_at' => now()->subWeeks(4)]);
        DB::table('dashboards')->update(['workflow_id' => 123]);
        $this->artisan('send-proposal-reminders')->assertSuccessful();
        DB::table('dashboards')->update(['workflow_id' => null]);
        DB::table('project_proposals')->update(['status_stage3' => 'pending']);
        $this->artisan('send-proposal-reminders')->assertSuccessful();
        \Illuminate\Support\Facades\Queue::assertNothingPushed();
    }

    public function test_inactive_reminder_emails_pi_with_instructions_and_rechecks_before_sending(): void
    {
        $this->addReminderColumns();
        $job = new \App\Jobs\ReminderEmails\SendInactiveProposalReminderEmail('proposal');
        $job->handle();
        Mail::assertSent(\App\Mail\Reminders\InactiveProposalReminder::class, function ($mail) {
            $html = $mail->render();
            $this->assertStringContainsString('Draft &lt;title&gt;', $html);
            $this->assertStringContainsString('View details', $html);
            $this->assertStringContainsString('Delete', $html);
            $this->assertStringContainsString('confirm the deletion', $html);
            $this->assertStringContainsString(route('pp.show', 'my'), $html);

            return $mail->hasTo('pi@example.test');
        });
        Mail::fake();
        DB::table('project_proposals')->update(['updated_at' => now()]);
        $job->handle();
        DB::table('project_proposals')->update(['updated_at' => now()->subWeeks(4)]);
        DB::table('dashboards')->update(['workflow_id' => 123]);
        $job->handle();
        DB::table('project_proposals')->delete();
        $job->handle();
        Mail::assertNothingSent();
    }
}
