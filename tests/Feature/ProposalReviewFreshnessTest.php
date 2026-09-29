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

    public function test_proposal_responses_prohibit_storage(): void
    {
        $response = (new SetProjectProposalsTitle)->handle(Request::create('/projectproposals'), fn () => response('Review'));
        $this->assertTrue($response->headers->hasCacheControlDirective('no-store'));
        $this->assertTrue($response->headers->hasCacheControlDirective('private'));
    }
}
