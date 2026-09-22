<?php

namespace Tests\Feature;

use App\Jobs\ReminderEmails\SendTravelRequestReminderEmail;
use App\Mail\Reminders\TravelRequestReminder;
use App\Models\TravelRequest;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TravelRequestReminderTest extends TestCase
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
        $this->freezeTime();
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('country')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_22_000000_add_reminder_tracking_to_travel_requests_table.php'))->up();
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'state', 'name', 'user_id', 'manager_id', 'head_id', 'fo_id'] as $field) {
                $table->string($field);
            }
        });
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
        });
        Schema::create('settings_fos', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->boolean('active');
        });
        foreach (['user', 'manager', 'head', 'fo', 'configured-fo'] as $id) {
            DB::table('users')->insert(['id' => $id, 'name' => $id, 'email' => $id.'@example.test']);
        }
        Mail::fake();
        Queue::fake();
    }

    private function request(string $state, string $id = 'trip'): TravelRequest
    {
        DB::table('travel_requests')->insert(['id' => $id]);
        DB::table('dashboards')->insert([
            'request_id' => $id, 'state' => $state, 'type' => 'travelrequest', 'name' => 'Conference',
            'user_id' => 'user', 'manager_id' => 'manager', 'head_id' => 'head', 'fo_id' => 'fo',
        ]);

        return TravelRequest::findOrFail($id);
    }

    public function test_each_pending_stage_sends_to_its_reviewer(): void
    {
        foreach (['submitted' => 'manager', 'manager_approved' => 'head', 'head_approved' => 'fo'] as $state => $role) {
            $this->request($state, $state);
            (new SendTravelRequestReminderEmail($state, $state))->handle();
            Mail::assertSent(TravelRequestReminder::class, fn ($mail) => $mail->hasTo($role.'@example.test'));
        }
        Mail::assertSentCount(3);
    }

    public function test_finance_uses_configured_recipients(): void
    {
        $this->request('head_approved');
        DB::table('settings_fos')->insert(['user_id' => 'configured-fo', 'active' => true]);
        (new SendTravelRequestReminderEmail('trip', 'head_approved'))->handle();
        Mail::assertSent(TravelRequestReminder::class, fn ($mail) => $mail->hasTo('configured-fo@example.test'));
        Mail::assertSentCount(1);
    }

    public function test_reminders_are_throttled_but_new_stages_are_not(): void
    {
        $request = $this->request('submitted');
        $this->artisan('send-travel-request-reminders')->assertSuccessful();
        $this->artisan('send-travel-request-reminders')->assertSuccessful();
        Queue::assertPushed(SendTravelRequestReminderEmail::class, 1);
        DB::table('dashboards')->update(['state' => 'manager_approved']);
        $this->artisan('send-travel-request-reminders')->assertSuccessful();
        Queue::assertPushed(SendTravelRequestReminderEmail::class, 2);
        $this->travel(3)->days();
        $this->artisan('send-travel-request-reminders')->assertSuccessful();
        Queue::assertPushed(SendTravelRequestReminderEmail::class, 3);
        $this->assertSame('head', $request->fresh()->last_reminder_type);
    }

    public function test_dry_run_limit_and_disabled_or_finished_requests(): void
    {
        $request = $this->request('submitted');
        $this->artisan('send-travel-request-reminders --dry-run')->assertSuccessful();
        Queue::assertNothingPushed();
        $this->assertNull($request->fresh()->last_reminder_sent_at);
        $request->forceFill(['reminder' => false])->save();
        foreach (['fo_approved', 'manager_returned', 'head_denied', 'pending'] as $state) {
            $this->request($state, $state);
        }
        $this->artisan('send-travel-request-reminders')->assertSuccessful();
        Queue::assertNothingPushed();
        $this->request('submitted', 'eligible-one');
        $this->request('submitted', 'eligible-two');
        $this->artisan('send-travel-request-reminders --limit=1')->assertSuccessful();
        Queue::assertPushed(SendTravelRequestReminderEmail::class, 1);
        $this->artisan('send-travel-request-reminders --limit=0')->assertExitCode(2);
    }

    public function test_queued_reminders_skip_changed_stages_and_disabled_requests(): void
    {
        $request = $this->request('manager_approved');
        (new SendTravelRequestReminderEmail('trip', 'submitted'))->handle();
        $request->forceFill(['reminder' => false])->save();
        (new SendTravelRequestReminderEmail('trip', 'manager_approved'))->handle();
        Mail::assertNothingSent();
    }

    public function test_email_contains_both_languages_and_review_link(): void
    {
        $request = $this->request('submitted');
        $request->update(['country' => 'Sweden', 'purpose' => 'Research & development
Present findings', 'total' => 12500]);
        $dashboard = $request->dashboard;
        $mail = new TravelRequestReminder($dashboard->user, $dashboard->user, $dashboard);
        $this->assertSame('emails.request.reminder', $mail->content()->view);
        $this->assertNull($mail->content()->text);
        $text = $mail->render();
        $this->assertStringContainsString('Country:</b> Sweden', $text);
        $this->assertStringContainsString('Land:</b> Sweden', $text);
        $this->assertStringContainsString('Total kostnad:</b> 12 500 SEK', $text);
        $this->assertStringContainsString('Total cost:</b> 12,500 SEK', $text);
        $this->assertStringContainsString('Research &amp; development<br />
Present findings', $text);
        $this->assertStringContainsString('<p>', $text);
        $this->assertStringContainsString('resebegäran', $text);
        $this->assertStringContainsString('awaiting your review', $text);
        $this->assertStringContainsString(route('travel-request-review', $dashboard->id), $text);
    }
}
