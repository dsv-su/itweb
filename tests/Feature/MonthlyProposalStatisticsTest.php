<?php

namespace Tests\Feature;

use App\Livewire\Pp\MonthlyStatsRecipients;
use App\Mail\MonthlyProposalStatistics;
use App\Models\SettingsVice;
use App\Models\User;
use App\Services\Stats\MonthlyProposalStats;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class MonthlyProposalStatisticsTest extends TestCase
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
        $this->travelTo(CarbonImmutable::parse('2026-01-01 21:00', 'Europe/Stockholm'));
        Schema::create('settings_vices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_23_000000_add_monthly_proposal_statistics.php'))->up();
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('pp');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('state');
        });
        Mail::fake();
    }

    private function recipients(array $recipients): void
    {
        $settings = SettingsVice::firstOrCreate();
        $settings->monthly_stats_recipients = $recipients;
        $settings->save();
    }

    private function proposal(string $id, string $deadline, string $state, string $currency = 'sek'): void
    {
        DB::table('project_proposals')->insert(['id' => $id, 'pp' => json_encode([
            'submission_deadline' => $deadline, 'budget_dsv' => 100.50,
            'cofinancing_needed' => 25, 'budget_phd' => 1.5, 'currency' => $currency,
            'research_area' => 'Computer science', 'funding_organization' => 'Agency <A>',
        ])]);
        DB::table('dashboards')->insert(['request_id' => $id, 'state' => $state]);
    }

    public function test_summary_uses_previous_calendar_month_and_keeps_currencies_separate(): void
    {
        $this->proposal('first', '2025-12-01', 'sent');
        $this->proposal('last', '2025-12-31', 'granted', 'eur');
        $this->proposal('before', '2025-11-30', 'sent');
        $this->proposal('after', '2026-01-01', 'sent');
        $this->proposal('draft', '2025-12-15', 'pending');
        DB::table('dashboards')->insert(['request_id' => 'first', 'state' => 'sent']);
        $stats = app(MonthlyProposalStats::class)->build(CarbonImmutable::parse('2025-12-01'));
        $this->assertSame(2, $stats['total']);
        $this->assertSame(1, $stats['granted']);
        $this->assertSame(1, $stats['awaiting']);
        $this->assertSame(100.5, $stats['budgets']['EUR']['granted']);
        $this->assertSame(0.0, $stats['budgets']['SEK']['granted']);
        $this->assertSame(100.5, $stats['budgets']['SEK']['requested']);
        $this->assertSame(3.0, $stats['phd_years']);
        $this->assertSame(['Computer science' => 2], $stats['research_subjects']);
        $mail = new MonthlyProposalStatistics($stats, ['name' => '<Recipient>']);
        $html = $mail->render();
        $this->assertStringContainsString('December 2025', $html);
        $this->assertStringContainsString('Agency &lt;A&gt;', $html);
        $this->assertStringContainsString('&lt;Recipient&gt;', $html);
        $this->assertStringContainsString('Awaiting outcome', $html);
    }

    public function test_delivery_is_individual_deduplicated_and_not_repeated_on_rerun(): void
    {
        $this->recipients([
            ['email' => 'one@example.com', 'name' => 'One'],
            ['email' => 'ONE@example.com', 'name' => 'Duplicate'],
            ['email' => 'two@example.com', 'name' => 'Two'],
            ['email' => 'invalid', 'name' => 'Invalid'],
        ]);
        $this->artisan('proposals:send-monthly-statistics')->assertSuccessful();
        $this->artisan('proposals:send-monthly-statistics')->assertSuccessful();
        Mail::assertSentCount(2);
        Mail::assertSent(MonthlyProposalStatistics::class, fn ($mail) => $mail->hasTo('one@example.com') && count($mail->to) === 1 && $mail->stats['month'] === 'December 2025'
        );
        $this->assertSame(2, DB::table('monthly_proposal_stat_deliveries')->where('month', '2025-12-01')->count());
        $this->travelTo(CarbonImmutable::parse('2026-02-01 21:00', 'Europe/Stockholm'));
        $this->artisan('proposals:send-monthly-statistics')->assertSuccessful();
        Mail::assertSentCount(4);
    }

    public function test_empty_recipients_and_dry_run_send_nothing(): void
    {
        $this->artisan('proposals:send-monthly-statistics')->assertSuccessful();
        $this->recipients([['email' => 'one@example.com', 'name' => 'One']]);
        $this->artisan('proposals:send-monthly-statistics --dry-run')->assertSuccessful();
        Mail::assertNothingSent();
        $this->assertSame(0, DB::table('monthly_proposal_stat_deliveries')->count());
        $stats = app(MonthlyProposalStats::class)->build(CarbonImmutable::parse('2025-12-01'));
        $this->assertStringContainsString('There were no sent or granted proposals', (new MonthlyProposalStatistics($stats, []))->render());
    }

    public function test_failed_delivery_does_not_block_others_and_can_be_retried(): void
    {
        $this->recipients([
            ['email' => 'one@example.com', 'name' => 'One'],
            ['email' => 'two@example.com', 'name' => 'Two'],
        ]);
        $mailFake = Mail::getFacadeRoot();
        $failure = \Mockery::mock(PendingMail::class);
        $failure->shouldReceive('send')->once()->andThrow(new \RuntimeException('Mail transport unavailable'));
        $success = \Mockery::mock(PendingMail::class);
        $success->shouldReceive('send')->once();
        Mail::shouldReceive('to')->with('one@example.com')->once()->andReturn($failure);
        Mail::shouldReceive('to')->with('two@example.com')->once()->andReturn($success);
        $this->artisan('proposals:send-monthly-statistics')->assertFailed();
        $this->assertSame(['two@example.com'], DB::table('monthly_proposal_stat_deliveries')->pluck('email')->all());
        Mail::swap($mailFake);
        $this->artisan('proposals:send-monthly-statistics')->assertSuccessful();
        Mail::assertSentCount(1);
        Mail::assertSent(MonthlyProposalStatistics::class, fn ($mail) => $mail->hasTo('one@example.com'));
    }

    public function test_manual_month_controls_report_and_delivery_tracking(): void
    {
        $this->recipients([['email' => 'one@example.com', 'name' => 'One']]);
        $this->proposal('february', '2024-02-29', 'granted');
        $this->proposal('march', '2024-03-01', 'sent');
        $this->artisan('proposals:send-monthly-statistics --month=2024-02 --dry-run')
            ->expectsOutput('February 2024: 1 configured recipients. No emails sent.')
            ->assertSuccessful();
        Mail::assertNothingSent();
        $this->artisan('proposals:send-monthly-statistics --month=2024-02')->assertSuccessful();
        $this->artisan('proposals:send-monthly-statistics --month=2024-02')->assertSuccessful();
        Mail::assertSentCount(1);
        Mail::assertSent(MonthlyProposalStatistics::class, fn ($mail) =>
            $mail->stats['month'] === 'February 2024' && $mail->stats['total'] === 1
        );
        $this->assertSame('2024-02-01', DB::table('monthly_proposal_stat_deliveries')->value('month'));
    }

    public function test_invalid_manual_month_is_rejected_without_sending(): void
    {
        foreach (['2026-13', '2026-00', '2026-2', '2026-02-01', 'invalid', ''] as $month) {
            $this->artisan('proposals:send-monthly-statistics', ['--month' => $month])->assertExitCode(2);
        }
        Mail::assertNothingSent();
        $this->assertSame(0, DB::table('monthly_proposal_stat_deliveries')->count());
    }

    public function test_schedule_runs_only_on_first_at_twenty_one_stockholm_time(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains($event->command ?? '', 'proposals:send-monthly-statistics'));
        $this->assertNotNull($event);
        $this->assertSame('0 21 1 * *', $event->expression);
        $this->assertSame('Europe/Stockholm', $event->timezone);
        $this->assertTrue($event->withoutOverlapping);
    }

    public function test_vice_can_save_remove_and_validate_recipients(): void
    {
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 'vice';
        $user->shouldReceive('isVice')->andReturn(true);
        $this->actingAs($user);
        Livewire::test(MonthlyStatsRecipients::class)
            ->call('addRecipient', 'sukat1', 'One', 'one@example.com', 'DSV')
            ->call('addRecipient', 'sukat1', 'One', 'one@example.com', 'DSV')
            ->call('save')->assertHasNoErrors();
        $this->assertCount(1, SettingsVice::first()->monthly_stats_recipients);
        Livewire::test(MonthlyStatsRecipients::class)
            ->call('removeRecipient', 0)->call('save')->assertHasNoErrors();
        $this->assertSame([], SettingsVice::first()->monthly_stats_recipients);
        Livewire::test(MonthlyStatsRecipients::class)
            ->call('addRecipient', 'sukat2', 'Two', 'invalid', 'DSV')
            ->call('save')->assertHasErrors('recipients.0.email');
        $this->assertSame([], SettingsVice::first()->monthly_stats_recipients);
    }

    public function test_unauthorized_users_cannot_access_recipient_settings(): void
    {
        Livewire::test(MonthlyStatsRecipients::class)->assertForbidden();
    }
}
