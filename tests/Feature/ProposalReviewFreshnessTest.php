<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProposalController;
use App\Http\Middleware\SetProjectProposalsTitle;
use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Services\Review\DashboardRole;
use App\Services\Review\ProposalFileReviewService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProposalReviewFreshnessTest extends \Tests\TestCase
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
            $table->string('email')->nullable();
        });
        foreach (['role_user' => 'role_id', 'group_user' => 'group_id'] as $name => $column) {
            Schema::create($name, function (Blueprint $table) use ($column) {
                $table->string('user_id');
                $table->string($column);
            });
        }
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->boolean('reminder')->default(true);
            $table->timestamps();
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'fo_id', 'state'] as $field) {
                $table->string($field);
            }
            $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 'old', 'name' => 'Previous Officer'],
            ['id' => 'new', 'name' => 'New Officer'],
            ['id' => 'outsider', 'name' => 'Other User'],
        ]);
        DB::table('role_user')->insert(['user_id' => 'old', 'role_id' => 'financial_officer']);
        DB::table('group_user')->insert(['user_id' => 'new', 'group_id' => 'ekonomi']);
        DB::table('project_proposals')->insert(['id' => 'trip']);
        DB::table('dashboards')->insert(['id' => 1, 'request_id' => 'trip', 'type' => 'projectproposal', 'fo_id' => 'old', 'state' => 'fo_approved']);
        DB::table('group_user')->insert(['user_id' => 'old', 'group_id' => 'ekonomi']);
        DB::table('users')->where('id', 'new')->update(['email' => 'new@example.test']);
        Mail::fake();
        $this->actingAs(User::find('old'));
    }

    public function test_role_checks_observe_changes_on_the_same_user_instance(): void
    {
        $user = User::findOrFail('old');
        $this->assertTrue($user->isFO());
        DB::table('role_user')->where('user_id', 'old')->delete();
        $this->assertFalse($user->isFO());
    }

    public function test_review_role_observes_reassignment_and_state_changes(): void
    {
        DB::table('dashboards')->where('id', 1)->update(['state' => 'head_approved']);
        $role = new DashboardRole(Dashboard::findOrFail(1), User::findOrFail('old'));
        $this->assertSame('fo', $role->check());
        DB::table('dashboards')->where('id', 1)->update(['fo_id' => 'new']);
        $this->assertFalse($role->check());
        DB::table('dashboards')->where('id', 1)->update(['fo_id' => 'old', 'state' => 'sent']);
        $this->assertFalse($role->check());
    }

    public function test_stale_decision_is_rejected_before_comments_or_signals(): void
    {
        DB::table('dashboards')->where('id', 1)->update(['state' => 'head_approved', 'fo_id' => 'new']);
        $request = Request::create('/projectproposals/decision', 'POST', ['id' => 'trip', 'decision' => 'approve']);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Unauthorized');
        app(ProposalController::class)->decision($request);
    }

    public function test_file_reviews_do_not_overwrite_files_from_a_stale_model(): void
    {
        Schema::table('project_proposals', fn (Blueprint $table) => $table->json('files')->nullable());
        DB::table('project_proposals')->where('id', 'trip')->update(['files' => json_encode([
            'old.pdf' => ['type' => 'draft', 'review' => 'pending'],
        ])]);
        $service = new ProposalFileReviewService(ProjectProposal::findOrFail('trip'));
        DB::table('project_proposals')->where('id', 'trip')->update(['files' => json_encode([
            'new.pdf' => ['type' => 'draft', 'review' => 'pending'],
        ])]);
        $service->approvePendingByType('draft');
        $this->assertSame(['new.pdf' => ['type' => 'draft', 'review' => 'approved']], ProjectProposal::findOrFail('trip')->files);
    }

    public function test_financial_officer_selection_ignores_old_cache_and_reads_current_settings(): void
    {
        foreach (['settings_fos', 'settings_fo_eus'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->boolean('active');
            });
            DB::table($tableName)->insert(['user_id' => 'old', 'active' => true]);
        }
        Cache::put('fo_ids', ['fo' => 'cached', 'fo_eu' => 'cached'], 600);
        $controller = app(ProposalController::class);
        $method = new \ReflectionMethod($controller, 'getFoIds');
        $this->assertSame(['fo' => 'old', 'fo_eu' => 'old'], $method->invoke($controller));
        DB::table('settings_fos')->update(['user_id' => 'new']);
        $this->assertSame(['fo' => 'new', 'fo_eu' => 'old'], $method->invoke($controller));
    }

    private function organizationReviewRequest(array $changes = []): Request
    {
        Schema::table('dashboards', fn (Blueprint $table) => $table->string('vice_id')->nullable());
        Schema::table('project_proposals', fn (Blueprint $table) => $table->json('pp')->nullable());
        DB::table('dashboards')->where('id', 1)->update(['vice_id' => 'old']);
        DB::table('project_proposals')->where('id', 'trip')->update(['pp' => json_encode([
            'program' => 'Original call', 'dsvcoordinating' => 'no',
            'other_coordination' => 'Coordinator', 'eu' => 'no', 'eu_wallenberg' => 'no',
            'budget_dsv' => 100, 'user_comments' => 'Existing comment',
        ])]);
        $request = Request::create('/projectproposals/submit', 'POST', ['id' => 'trip', ...$changes]);
        $request->setUserResolver(fn () => User::findOrFail('old'));

        return $request;
    }

    public function test_vice_can_revise_organization_with_audit_comments_but_not_budget(): void
    {
        $request = $this->organizationReviewRequest(['program' => 'Revised call', 'budget_dsv' => 999]);
        $method = new \ReflectionMethod(ProposalController::class, 'handleReview');
        $method->invoke(app(ProposalController::class), $request, now());
        $pp = ProjectProposal::findOrFail('trip')->pp;
        $this->assertSame('Revised call', $pp['program']);
        $this->assertSame(100, $pp['budget_dsv']);
        $this->assertStringStartsWith('Existing comment', $pp['user_comments']);
        $this->assertStringContainsString('REVISED by Previous Officer', $pp['user_comments']);
        $this->assertStringContainsString('"Original call" → "Revised call"', $pp['user_comments']);
        $method->invoke(app(ProposalController::class), $request, now());
        $this->assertSame($pp['user_comments'], ProjectProposal::findOrFail('trip')->pp['user_comments']);
    }

    public function test_vice_organization_revision_normalizes_dependent_fields(): void
    {
        $request = $this->organizationReviewRequest(['dsvcoordinating' => 'yes', 'eu' => 'yes', 'eu_wallenberg' => 'yes']);
        (new \ReflectionMethod(ProposalController::class, 'handleReview'))
            ->invoke(app(ProposalController::class), $request, now());
        $pp = ProjectProposal::findOrFail('trip')->pp;
        $this->assertSame('', $pp['other_coordination']);
        $this->assertSame('no', $pp['eu_wallenberg']);
        $this->assertStringContainsString('Other coordinator:', $pp['user_comments']);
    }

    public function test_vice_cannot_revise_organization_after_final_approval(): void
    {
        $request = $this->organizationReviewRequest(['program' => 'Unauthorized call']);
        DB::table('dashboards')->where('id', 1)->update(['state' => 'final_approved']);
        try {
            (new \ReflectionMethod(ProposalController::class, 'handleReview'))
                ->invoke(app(ProposalController::class), $request, now());
            $this->fail('Expected stale review to be rejected');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        $this->assertSame('Original call', ProjectProposal::findOrFail('trip')->pp['program']);
    }

    private function approvedNotification(string $type = 'projectproposal'): \App\Mail\NotifyRequestApproved
    {
        $dashboard = Dashboard::findOrFail(1);
        $dashboard->forceFill(['type' => $type, 'state' => $type === 'travelrequest' ? 'fo_approved' : 'final_approved',
            'name' => 'Test request', 'created' => 1700000000]);

        return new \App\Mail\NotifyRequestApproved(User::findOrFail('old'), $dashboard);
    }

    public function test_approved_email_summarizes_net_vice_changes_across_saves(): void
    {
        $request = $this->organizationReviewRequest(['program' => 'Intermediate call']);
        $method = new \ReflectionMethod(ProposalController::class, 'handleReview');
        $method->invoke(app(ProposalController::class), $request, now());
        $request->merge(['program' => 'Final <call>', 'dsvcoordinating' => 'yes']);
        $method->invoke(app(ProposalController::class), $request, now());
        $mail = $this->approvedNotification();
        $changes = $mail->content()->with['organizationChanges'];
        $this->assertCount(3, $changes);
        $this->assertSame('Original call', $changes[0]['before']);
        $this->assertSame('Final <call>', $changes[0]['after']);
        $this->assertSame('Yes', $changes[1]['after']);
        $html = $mail->render();
        $this->assertStringContainsString('Project Organization changes', $html);
        $this->assertStringContainsString('Final &lt;call&gt;', $html);
        $this->assertStringNotContainsString('Intermediate call', $html);
        $this->assertStringContainsString('Not specified', $html);
        $this->assertStringContainsString('Before review', $html);
    }

    public function test_reverted_vice_changes_are_absent_from_approved_email(): void
    {
        $request = $this->organizationReviewRequest(['program' => 'Changed']);
        $method = new \ReflectionMethod(ProposalController::class, 'handleReview');
        $method->invoke(app(ProposalController::class), $request, now());
        $request->merge(['program' => 'Original call']);
        $method->invoke(app(ProposalController::class), $request, now());
        $mail = $this->approvedNotification();
        $this->assertSame([], $mail->content()->with['organizationChanges']);
        $this->assertStringNotContainsString('Project Organization changes', $mail->render());
    }

    public function test_approved_email_omits_vice_values_replaced_by_another_editor(): void
    {
        $request = $this->organizationReviewRequest(['program' => 'Vice call']);
        (new \ReflectionMethod(ProposalController::class, 'handleReview'))
            ->invoke(app(ProposalController::class), $request, now());
        $proposal = ProjectProposal::findOrFail('trip');
        $proposal->update(['pp' => [...$proposal->pp, 'program' => 'Applicant call']]);
        $this->assertSame([], $this->approvedNotification()->content()->with['organizationChanges']);
    }

    public function test_approved_email_without_vice_edits_keeps_existing_content(): void
    {
        $this->organizationReviewRequest();
        $mail = $this->approvedNotification();
        $this->assertSame([], $mail->content()->with['organizationChanges']);
        $this->assertStringNotContainsString('Project Organization changes', $mail->render());
        $this->assertStringContainsString('You may now submit your application.', $mail->render());
    }

    public function test_travel_approval_email_does_not_query_project_proposals(): void
    {
        Schema::drop('project_proposals');
        $mail = $this->approvedNotification('travelrequest');
        $this->assertSame([], $mail->content()->with['organizationChanges']);
        $html = $mail->render();
        $this->assertStringNotContainsString('Project Organization changes', $html);
        $this->assertStringContainsString('GODKÄND RESEBEGÄRAN', $html);
        $this->assertStringContainsString('Bon Voyage', $html);
    }

    public function test_organization_review_templates_compile(): void
    {
        foreach (['pp/create', 'pp/partials/review/organization_changes', 'pp/partials/form/program_call', 'pp/partials/review/bar'] as $template) {
            $compiled = app('blade.compiler')->compileString(file_get_contents(resource_path('views/'.$template.'.blade.php')));
            $this->assertNotEmpty(token_get_all($compiled, TOKEN_PARSE));
            $this->assertStringNotContainsString('@endif', $compiled);
        }
    }

    public function test_proposal_responses_prohibit_storage(): void
    {
        $response = (new SetProjectProposalsTitle)->handle(Request::create('/projectproposals'), fn () => response('Review'));
        $this->assertTrue($response->headers->hasCacheControlDirective('no-store'));
        $this->assertTrue($response->headers->hasCacheControlDirective('private'));
    }
}
