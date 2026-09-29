<?php

namespace Tests\Feature;

use App\Http\Controllers\ReviewController;
use App\Http\Middleware\DSVStaffEntitlement;
use App\Livewire\Requestnotifications;
use App\Models\Dashboard;
use App\Models\User;
use App\Services\Review\RequestReviewHandler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery\MockInterface;
use Statamic\StaticCaching\DefaultUrlExcluder;
use Tests\TestCase;

class TravelRequestReviewAccessTest extends TestCase
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

    private function setUpProjectReview(): void
    {
        Schema::create('settings_fos', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->boolean('active');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'state', 'fo_id', 'head_id', 'manager_id'] as $column) {
                $table->string($column);
            }
        });
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('project')->nullable();
            $table->timestamps();
        });
        foreach (['head_comments', 'fo_comments'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('reqid');
                $table->string('user_id');
                $table->text('comment');
                $table->timestamps();
            });
        }
        DB::table('settings_fos')->insert([
            ['user_id' => 'first-fo', 'active' => true],
            ['user_id' => 'second-fo', 'active' => true],
            ['user_id' => 'inactive-fo', 'active' => false],
        ]);
        DB::table('travel_requests')->insert(['id' => 'trip', 'project' => 'original']);
        DB::table('dashboards')->insert([
            'id' => 42, 'request_id' => 'trip', 'type' => 'travelrequest',
            'state' => 'head_approved', 'fo_id' => 'first-fo',
            'head_id' => 'head', 'manager_id' => 'manager',
        ]);
        $this->withoutMiddleware([DSVStaffEntitlement::class, ValidateCsrfToken::class]);
        $this->withSession(['locale' => 'en']);
    }

    public function test_all_active_fos_get_the_editable_form_and_can_update_the_project(): void
    {
        $this->setUpProjectReview();
        foreach (['first-fo', 'second-fo', 'inactive-fo', 'unrelated'] as $userId) {
            $user = new User;
            $user->id = $userId;
            $this->actingAs($user);
            $active = in_array($userId, ['first-fo', 'second-fo'], true);
            $controller = new ReviewController;

            foreach ([$controller->show(42), $controller->showLocalized('swe', 42)] as $view) {
                $this->assertSame($active ? 'fo_review' : 'review', $view->data()['formtype']);
            }

            $response = $this->post('/fo_review/42', ['decision' => 'update', 'project' => $userId]);
            if ($active) {
                $response->assertRedirect();
                $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => $userId]);
                $this->assertDatabaseHas('fo_comments', [
                    'reqid' => 'trip', 'user_id' => $userId,
                    'comment' => 'Project changed from '.($userId === 'first-fo' ? 'original' : 'first-fo').' to '.$userId.'.',
                ]);
                $this->post('/fo_review/42', ['decision' => 'update', 'project' => $userId])->assertRedirect();
            } else {
                $response->assertForbidden();
                $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => 'second-fo']);
            }
        }
        $this->assertDatabaseCount('fo_comments', 2);
        $this->assertDatabaseCount('head_comments', 0);
    }

    public function test_head_can_update_project_only_during_their_review_stage(): void
    {
        $this->setUpProjectReview();

        DB::table('dashboards')->where('id', 42)->update(['state' => 'manager_approved']);
        $head = new User;
        $head->id = 'head';
        $this->actingAs($head);

        $controller = new ReviewController;
        foreach ([$controller->show(42), $controller->showLocalized('swe', 42)] as $view) {
            $this->assertTrue($view->data()['canUpdateProject']);
        }
        $this->post('/review/42', ['decision' => 'update', 'project' => 'head-project'])->assertRedirect();
        $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => 'head-project']);
        $this->assertDatabaseHas('dashboards', ['id' => 42, 'state' => 'manager_approved']);
        $this->assertDatabaseHas('head_comments', [
            'reqid' => 'trip', 'user_id' => 'head',
            'comment' => 'Project changed from original to head-project.',
        ]);
        $this->post('/review/42', ['decision' => 'update', 'project' => 'head-project'])->assertRedirect();
        $this->assertDatabaseCount('head_comments', 1);
        $this->assertDatabaseCount('fo_comments', 0);

        DB::table('dashboards')->where('id', 42)->update(['state' => 'submitted']);
        $this->assertFalse($controller->show(42)->data()['canUpdateProject']);
        $this->post('/review/42', ['decision' => 'update', 'project' => 'forbidden'])->assertForbidden();

        $manager = new User;
        $manager->id = 'manager';
        $this->actingAs($manager);
        $this->post('/review/42', ['decision' => 'update', 'project' => 'forbidden'])->assertForbidden();
        $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => 'head-project']);
        $this->assertDatabaseCount('head_comments', 1);
    }

    public function test_direct_link_only_allows_the_reviewer_for_the_current_stage(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'state', 'manager_id', 'head_id', 'fo_id', 'vice_id'] as $column) {
                $table->string($column)->nullable();
            }
        });

        Schema::create('settings_fos', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->boolean('active');
        });

        DB::table('settings_fos')->insert([
            ['user_id' => 'active-fo', 'active' => true],
            ['user_id' => 'inactive-fo', 'active' => false],
        ]);

        DB::table('dashboards')->insert([
            'id' => 42,
            'request_id' => 'travel-request-id',
            'type' => 'travelrequest',
            'state' => 'submitted',
            'manager_id' => 'manager',
            'head_id' => 'head',
            'fo_id' => 'fo',
        ]);

        $this->withoutMiddleware(DSVStaffEntitlement::class);
        $this->partialMock(ReviewController::class, function (MockInterface $mock) {
            $mock->makePartial();
            (new \ReflectionMethod(ReviewController::class, '__construct'))->invoke($mock);
            $mock->shouldReceive('show')->with('42')->andReturn(response('Review'));
            $mock->shouldReceive('showLocalized')->with('swe', '42')->andReturn(response('Review'));
        });

        foreach (['submitted' => 'manager', 'manager_approved' => 'head', 'head_approved' => 'fo'] as $state => $reviewer) {
            DB::table('dashboards')->where('id', 42)->update(['state' => $state]);

            foreach (['manager', 'head', 'fo', 'active-fo', 'inactive-fo', 'unrelated'] as $userId) {
                $user = new User;
                $user->id = $userId;
                $this->actingAs($user);

                foreach (['/travel/review/42', '/swe/travel/review/42'] as $url) {
                    $allowed = $userId === $reviewer || ($state === 'head_approved' && $userId === 'active-fo');
                    $this->get($url)->assertStatus($allowed ? 200 : 403);
                }
            }
        }

        DB::table('dashboards')->where('id', 42)->update(['type' => 'projectproposal']);

        foreach (['fo', 'active-fo', 'inactive-fo'] as $userId) {
            $user = new User;
            $user->id = $userId;
            $this->actingAs($user);

            $this->get('/travel/review/42')->assertStatus($userId === 'fo' ? 200 : 403);
        }
    }

    public function test_review_handler_rejects_a_reassigned_reviewer_using_an_older_dashboard(): void
    {
        $this->setUpProjectReview();
        $user = new User;
        $user->id = 'first-fo';
        $handler = new RequestReviewHandler(
            Dashboard::findOrFail(42), $user, 'Stale review', 'approve'
        );
        DB::table('dashboards')->where('id', 42)->update(['fo_id' => 'second-fo']);

        try {
            $handler->review();
            $this->fail('A reassigned reviewer was accepted.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Reviewer is not allowed to review this request in the current state.', $exception->getMessage());
        }
        $this->assertDatabaseCount('fo_comments', 0);
        $this->assertDatabaseHas('dashboards', ['id' => 42, 'state' => 'head_approved']);
    }

    public function test_review_handler_rechecks_the_stage_before_writing(): void
    {
        $this->setUpProjectReview();
        $user = new User;
        $user->id = 'first-fo';
        $handler = new RequestReviewHandler(
            Dashboard::findOrFail(42), $user, 'Duplicate review', 'approve'
        );
        DB::table('dashboards')->where('id', 42)->update(['state' => 'fo_approved']);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Reviewer is not allowed to review this request in the current state.');
        $handler->review();
    }

    public function test_active_officer_access_is_not_retained_after_deactivation(): void
    {
        $this->setUpProjectReview();
        $user = new User;
        $user->id = 'second-fo';
        $this->actingAs($user);
        $this->post('/fo_review/42', ['decision' => 'update', 'project' => 'allowed'])->assertRedirect()
            ->assertHeader('Cache-Control', 'max-age=0, no-store, private');
        DB::table('settings_fos')->where('user_id', 'second-fo')->update(['active' => false]);
        $this->post('/fo_review/42', ['decision' => 'update', 'project' => 'stale'])->assertForbidden();
        $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => 'allowed']);
    }

    public function test_non_assigned_officer_cannot_change_project_when_submitting_a_decision(): void
    {
        $this->setUpProjectReview();
        $user = new User;
        $user->id = 'second-fo';
        $this->actingAs($user);
        $this->post('/fo_review/42', ['decision' => 'approve', 'project' => 'forbidden'])->assertForbidden();
        $this->assertDatabaseHas('travel_requests', ['id' => 'trip', 'project' => 'original']);
        $this->assertDatabaseCount('fo_comments', 0);
    }

    public function test_notification_lists_requery_current_assignments_on_each_render(): void
    {
        $this->setUpProjectReview();
        Schema::table('dashboards', function (Blueprint $table) {
            $table->string('user_id')->nullable();
            $table->string('vice_id')->nullable();
            $table->json('unit_head_approved')->nullable();
        });
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('files')->nullable();
        });
        $user = new User;
        $user->id = 'first-fo';
        $this->actingAs($user);
        $component = new Requestnotifications;
        $this->assertCount(1, $component->render()->getData()['requests']);
        DB::table('dashboards')->where('id', 42)->update(['fo_id' => 'second-fo']);
        $this->assertCount(0, $component->render()->getData()['requests']);
        $user->id = 'second-fo';
        $this->assertCount(1, $component->render()->getData()['requests']);
        DB::table('dashboards')->where('id', 42)->update(['state' => 'fo_approved']);
        $this->assertCount(0, $component->render()->getData()['requests']);
        $this->assertFalse(property_exists($component, 'user_roles'));
    }

    public function test_travel_and_localized_review_urls_are_excluded_from_static_caching(): void
    {
        $config = require base_path('config/statamic/static_caching.php');
        $excluder = new DefaultUrlExcluder('https://example.test', $config['exclude']['urls']);
        foreach (['', '/en', '/sv', '/swe'] as $prefix) {
            foreach (['/travel', '/travel/review/42', '/travelresume/trip', '/list', '/show/trip', '/notifications'] as $path) {
                $this->assertTrue($excluder->isExcluded('https://example.test'.$prefix.$path));
            }
        }
    }
}
