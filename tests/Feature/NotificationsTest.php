<?php

namespace Tests\Feature;

use App\Livewire\Notifications;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationsTest extends TestCase
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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['name', 'user_id', 'manager_id', 'head_id', 'fo_id', 'vice_id', 'request_id', 'type', 'state', 'status', 'unit_head_approved'] as $column) {
                $table->string($column)->nullable();
            }
            $table->integer('created')->nullable();
            $table->json('unit_heads')->nullable();
            $table->timestamps();
        });
        foreach (['travel_requests', 'project_proposals'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->string('id')->primary();
                $table->json('files')->nullable();
            });
        }
        DB::table('users')->insert(['id' => 'owner', 'name' => 'Request Owner']);
        $user = new User;
        $user->id = 'owner';
        $user->name = 'Request Owner';
        $this->actingAs($user);
        foreach ([
            [1, 'My returned trip', 'owner', null, 'manager_returned'],
            [2, 'Review this trip', 'other', 'owner', 'submitted'],
            [3, 'Private trip', 'other', 'someone-else', 'submitted'],
        ] as [$id, $name, $owner, $manager, $state]) {
            DB::table('dashboards')->insert([
                'id' => $id, 'name' => $name, 'user_id' => $owner, 'manager_id' => $manager,
                'type' => 'travelrequest', 'state' => $state, 'status' => 'unread',
                'created_at' => '2026-09-17 09:00:00', 'updated_at' => '2026-09-17 10:00:00',
            ]);
        }
    }

    public function test_notifications_are_scoped_and_filters_work(): void
    {
        Livewire::test(Notifications::class)
            ->assertSee('My returned trip')->assertSee('Review this trip')->assertDontSee('Private trip')
            ->set('category', 'review')->assertSee('Review this trip')->assertDontSee('My returned trip')
            ->set('category', 'returned')->assertSee('My returned trip')->assertDontSee('Review this trip')
            ->set('category', 'all')->set('search', 'Private trip')->assertSee(__('No notifications found'))
            ->set('search', '')->set('state', 'submitted')->assertSee('Review this trip')->assertDontSee('My returned trip')
            ->set('type', 'projectproposal')->assertSee(__('No notifications found'));
    }

    public function test_mark_read_only_changes_owned_requests_and_preserves_workflow_date(): void
    {
        Livewire::test(Notifications::class)->call('markRead', 2)->call('markRead', 3)->call('markRead', 1);
        $this->assertSame('read', DB::table('dashboards')->where('id', 1)->value('status'));
        $this->assertSame('2026-09-17 10:00:00', DB::table('dashboards')->where('id', 1)->value('updated_at'));
        $this->assertSame('unread', DB::table('dashboards')->where('id', 2)->value('status'));
        $this->assertSame('unread', DB::table('dashboards')->where('id', 3)->value('status'));
    }

    public function test_heads_can_filter_their_approved_requests_and_open_view_links(): void
    {
        foreach ([
            [10, 'Approved trip', 'travelrequest', 'head_approved', 'owner', null, null],
            [11, 'Completed trip', 'travelrequest', 'fo_approved', 'owner', null, null],
            [12, 'Another heads trip', 'travelrequest', 'fo_approved', 'someone-else', null, null],
            [13, 'Trip awaiting approval', 'travelrequest', 'manager_approved', 'owner', null, null],
            [14, 'Approved proposal', 'projectproposal', 'final_approved', null, ['owner'], ['owner' => 1]],
            [15, 'Partially approved proposal', 'projectproposal', 'complete', null, ['owner', 'other'], ['owner' => 1, 'other' => 0]],
            [16, 'Unapproved proposal', 'projectproposal', 'complete', null, ['owner'], ['owner' => 0]],
            [17, 'Another heads proposal', 'projectproposal', 'final_approved', null, ['other'], ['other' => 1]],
            [18, 'Returned trip', 'travelrequest', 'fo_returned', 'owner', null, null],
        ] as [$id, $name, $type, $state, $head, $heads, $approvals]) {
            DB::table('dashboards')->insert([
                'id' => $id, 'name' => $name, 'type' => $type, 'state' => $state,
                'user_id' => 'other', 'head_id' => $head, 'request_id' => 'request-'.$id,
                'unit_heads' => $heads ? json_encode($heads) : null,
                'unit_head_approved' => $approvals ? json_encode($approvals) : null,
            ]);
        }

        Livewire::test(Notifications::class)
            ->assertSee(__('Approved by you'))
            ->assertViewHas('counts', fn ($counts) => $counts['approved'] === 4)
            ->set('category', 'approved')
            ->assertSee('Approved trip')->assertSee('Completed trip')
            ->assertSee('Approved proposal')->assertSee('Partially approved proposal')
            ->assertDontSee('Another heads trip')->assertDontSee('Another heads proposal')
            ->assertDontSee('Unapproved proposal')->assertDontSee('Trip awaiting approval')
            ->assertDontSee('Returned trip')->assertDontSee('My returned trip')
            ->assertSee(route('travel-request-show', 10), false)
            ->assertSee(route('pp.review.view', 'request-14'), false)
            ->assertDontSee(route('travel-request-review', 10), false)
            ->set('type', 'projectproposal')->assertDontSee('Approved trip')
            ->set('search', 'Partially')->assertSee('Partially approved proposal')->assertDontSee('Approved proposal');
    }

    public function test_non_heads_cannot_access_approved_requests_by_changing_the_category(): void
    {
        DB::table('dashboards')->insert([
            'name' => 'Another heads approved trip', 'user_id' => 'other', 'head_id' => 'other',
            'type' => 'travelrequest', 'state' => 'fo_approved',
        ]);
        Livewire::test(Notifications::class)->assertDontSee(__('Approved by you'))
            ->set('category', 'approved')->assertSee(__('No notifications found'))
            ->assertDontSee('Another heads approved trip');
    }

    public function test_view_routes_allow_assigned_heads_and_reject_unrelated_users(): void
    {
        DB::table('dashboards')->insert([
            'id' => 42, 'request_id' => 'a6b41c87-a0ee-43a4-bc2c-ab3d77868ce4', 'user_id' => 'other',
            'type' => 'projectproposal', 'state' => 'final_approved', 'unit_heads' => json_encode(['owner']),
        ]);
        DB::table('project_proposals')->insert(['id' => 'a6b41c87-a0ee-43a4-bc2c-ab3d77868ce4']);
        DB::table('dashboards')->insert([
            'id' => 43, 'user_id' => 'other', 'head_id' => 'owner',
            'type' => 'travelrequest', 'state' => 'fo_approved',
        ]);
        $this->withoutMiddleware(\App\Http\Middleware\DSVStaffEntitlement::class);
        foreach ([
            \App\Http\Controllers\TravelRequestController::class => ['show', 'showLocalized'],
            \App\Http\Controllers\ReviewController::class => ['pp_view'],
        ] as $controller => $methods) {
            $this->partialMock($controller, function ($mock) use ($controller, $methods) {
                $mock->makePartial();
                (new \ReflectionMethod($controller, '__construct'))->invoke($mock);
                foreach ($methods as $method) {
                    $mock->shouldReceive($method)->andReturn(response('Request details'));
                }
            });
        }
        foreach (['owner' => 200, 'unrelated' => 403] as $id => $status) {
            $user = new User;
            $user->id = $id;
            $this->actingAs($user);
            foreach (['/travel/show/43', '/swe/travel/show/43', '/projectproposals/review/view/a6b41c87-a0ee-43a4-bc2c-ab3d77868ce4'] as $url) {
                $this->get($url)->assertStatus($status);
            }
        }
    }

    public function test_returned_travel_email_links_allow_the_owner_and_reject_unrelated_users(): void
    {
        $this->withoutMiddleware(\App\Http\Middleware\DSVStaffEntitlement::class);
        $this->partialMock(\App\Http\Controllers\TravelRequestController::class, function ($mock) {
            $mock->makePartial();
            (new \ReflectionMethod(\App\Http\Controllers\TravelRequestController::class, '__construct'))->invoke($mock);
            $mock->shouldReceive('show')->with('1')->andReturn(response('Request details'));
            $mock->shouldReceive('showLocalized')->with('swe', '1')->andReturn(response('Request details'));
        });

        foreach (['manager_returned', 'head_returned', 'fo_returned'] as $state) {
            DB::table('dashboards')->where('id', 1)->update(['state' => $state, 'request_id' => 'different-request-id']);
            $dashboard = \App\Models\Dashboard::findOrFail(1);
            $owner = User::findOrFail('owner');
            $html = (new \App\Mail\NotifyUserChangedState($owner, $owner, $dashboard))->render();
            $url = route('travel-request-show', $dashboard->id);
            $this->assertSame(2, substr_count($html, 'href="'.$url.'"'));

            foreach (['owner' => 200, 'unrelated' => 403] as $id => $status) {
                $user = new User;
                $user->id = $id;
                $this->actingAs($user);
                $this->get($url)->assertStatus($status);
                $this->get('/swe/travel/show/1')->assertStatus($status);
            }
        }

        auth()->logout();
        $this->get(route('travel-request-show', 1))->assertRedirect(route('login'));
    }

    public function test_proposal_reviews_require_assignment_and_uploaded_files(): void
    {
        foreach (['ready' => ['a.pdf', 'b.pdf'], 'incomplete' => ['a.pdf']] as $id => $files) {
            DB::table('project_proposals')->insert(['id' => $id, 'files' => json_encode($files)]);
            DB::table('dashboards')->insert([
                'name' => 'Proposal '.$id, 'user_id' => 'other', 'request_id' => $id,
                'type' => 'projectproposal', 'state' => 'complete', 'status' => 'unread',
                'unit_head_approved' => json_encode(['owner' => 0]),
            ]);
        }
        Livewire::test(Notifications::class)->set('category', 'review')
            ->assertSee('Proposal ready')->assertDontSee('Proposal incomplete')
            ->assertSee(route('pp.review.show', 'ready'), false);
    }

    public function test_older_requests_are_paginated_and_filtering_resets_the_page(): void
    {
        for ($i = 0; $i < 20; $i++) {
            DB::table('dashboards')->insert([
                'name' => 'Older request '.$i, 'user_id' => 'owner',
                'type' => 'travelrequest', 'state' => 'submitted', 'status' => 'read',
                'updated_at' => '2026-01-01 09:00:00',
            ]);
        }
        Livewire::test(Notifications::class)
            ->assertSee('My returned trip')->assertDontSee('Older request 0<', false)
            ->call('gotoPage', 2)->assertSee('Older request 0')->assertDontSee('My returned trip')
            ->set('search', 'My returned trip')->assertSee('My returned trip');
    }

    public function test_read_action_remains_available_to_focus_and_announces_success(): void
    {
        $component = Livewire::test(Notifications::class)
            ->call('markRead', 1)
            ->assertSet('feedback', __('Marked :name as read.', ['name' => 'My returned trip']));
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="utf-8" ?>'.$component->html());
        $xpath = new \DOMXPath($document);
        $buttons = $xpath->query('//button[contains(@*[name()="wire:click"], "markRead(1)")]');
        $this->assertCount(1, $buttons);
        $this->assertSame('true', $buttons->item(0)->getAttribute('aria-disabled'));
        $this->assertFalse($buttons->item(0)->hasAttribute('disabled'));
        $this->assertStringContainsString('My returned trip', $buttons->item(0)->textContent);
        $this->assertGreaterThan(0, $xpath->query('//*[@role="status" and @aria-atomic="true"]')->length);
        $component->call('markRead', 1)->assertOk();
    }

    public function test_refresh_is_explicit_and_announces_completion(): void
    {
        Livewire::test(Notifications::class)
            ->assertDontSee('wire:poll', false)
            ->call('refreshNotifications')
            ->assertSet('feedback', __('Notifications refreshed.'));
    }

    public function test_notification_count_is_inside_the_link_and_has_an_accessible_name(): void
    {
        $html = view('navbar.partials.notifications_link', [
            'notificationLinkClasses' => 'inline-flex h-11 w-11',
        ])->render();
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        $xpath = new \DOMXPath($document);
        $link = $xpath->query('//a')->item(0);
        $this->assertStringContainsString(__('Notifications'), $link->textContent);
        $this->assertStringContainsString(__('Notifications requiring attention'), $link->textContent);
        $this->assertSame(1, $xpath->query('//a//span[contains(@class, "absolute") and contains(@class, "right-0")]')->length);
        $this->assertStringNotContainsString('animate-ping', $html);
    }

    public function test_notification_badge_caps_visual_count_but_preserves_accessible_count(): void
    {
        $html = view('livewire.indicator', ['dashboard' => collect(range(1, 120))])->render();
        $this->assertStringContainsString('99+', $html);
        $this->assertStringContainsString(': 120', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $empty = view('livewire.indicator', ['dashboard' => collect()])->render();
        $this->assertStringNotContainsString('bg-blue-700', $empty);
    }

    public function test_guests_cannot_open_notifications(): void
    {
        auth()->logout();
        foreach (['/notifications', '/swe/notifications'] as $url) {
            $this->get($url)->assertRedirect();
        }
    }
}
