<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\SettingsFo;
use App\Models\SettingsFoEu;
use App\Services\Finance\FinancialOfficerRecipients;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FinancialOfficerSettingsTest extends TestCase
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
            $table->string('email');
        });
        Schema::create('group_user', function (Blueprint $table) {
            $table->string('group_id');
            $table->string('user_id');
        });
        foreach (['settings_fos', 'settings_fo_eus'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->string('user_id')->unique();
                $table->string('name');
                $table->boolean('active');
                $table->timestamps();
            });
        }
        foreach (['one', 'two', 'outsider'] as $id) {
            DB::table('users')->insert(['id' => $id, 'name' => $id, 'email' => "$id@example.com"]);
        }
        foreach (['one', 'two'] as $id) {
            DB::table('group_user')->insert(['group_id' => 'ekonomi', 'user_id' => $id]);
        }
        $this->withoutMiddleware();
    }

    public function test_multiple_officers_can_be_saved_and_removed_for_each_category(): void
    {
        foreach (['selected_fo' => ['/fo', SettingsFo::class], 'selected_fo_eu' => ['/fo_eu', SettingsFoEu::class]] as $field => [$url, $model]) {
            Cache::put('fo_ids', ['fo' => 'stale']);
            $this->post($url, [$field => ['one', 'two']])->assertRedirect();
            $this->assertEqualsCanonicalizing(['one', 'two'], $model::pluck('user_id')->all());
            $this->assertNull(Cache::get('fo_ids'));
            $this->post($url, [$field => ['two']])->assertRedirect();
            $this->assertSame(['two'], $model::pluck('user_id')->all());
        }
    }

    public function test_invalid_selections_leave_existing_settings_intact(): void
    {
        SettingsFo::create(['user_id' => 'one', 'name' => 'one', 'active' => true]);
        foreach ([[], ['outsider'], ['missing'], ['one', 'one'], 'one'] as $selection) {
            $this->postJson('/fo', ['selected_fo' => $selection])->assertUnprocessable();
            $this->assertSame(['one'], SettingsFo::pluck('user_id')->all());
        }
    }

    public function test_settings_form_displays_both_saved_selections(): void
    {
        foreach (['one', 'two'] as $id) {
            SettingsFo::create(['user_id' => $id, 'name' => $id, 'active' => true]);
        }
        $html = view('requests.fo.recipients', [
            'settingsModel' => SettingsFo::class,
            'field' => 'selected_fo',
            'routeName' => 'fo',
            'fos' => \App\Models\User::whereIn('id', ['one', 'two'])->get(),
            'errors' => new \Illuminate\Support\ViewErrorBag,
        ])->render();
        $this->assertStringContainsString('Current recipients: one, two', $html);
        $this->assertSame(2, substr_count($html, 'checked'));
        $this->assertStringContainsString('name="selected_fo[]"', $html);
    }

    public function test_travel_notification_is_sent_to_both_selected_officers(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            foreach (['user_id', 'manager_id', 'head_id', 'fo_id'] as $column) {
                $table->string($column);
            }
        });
        DB::table('dashboards')->insert([
            'id' => 1, 'type' => 'travelrequest', 'user_id' => 'outsider',
            'manager_id' => 'one', 'head_id' => 'one', 'fo_id' => 'one',
        ]);
        foreach (['one', 'two'] as $id) {
            SettingsFo::create(['user_id' => $id, 'name' => $id, 'active' => true]);
        }
        \Illuminate\Support\Facades\Mail::fake();
        $activity = (new \ReflectionClass(\App\Workflows\Notifications\NewRequestNotification::class))
            ->newInstanceWithoutConstructor();
        $activity->execute('fo', 1);
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\NotifyRequestFO::class, 2);
        foreach (['one', 'two'] as $id) {
            \Illuminate\Support\Facades\Mail::assertSent(
                \App\Mail\NotifyRequestFO::class,
                fn ($mail) => $mail->hasTo("$id@example.com")
            );
        }
    }

    public function test_recipients_are_separated_by_category_with_legacy_fallback(): void
    {
        foreach (['one', 'two'] as $id) {
            SettingsFo::create(['user_id' => $id, 'name' => $id, 'active' => true]);
        }
        SettingsFoEu::create(['user_id' => 'two', 'name' => 'two', 'active' => true]);
        $dashboard = new Dashboard(['type' => 'travelrequest', 'fo_id' => 'one']);
        $this->assertEqualsCanonicalizing(['one', 'two'], FinancialOfficerRecipients::forDashboard($dashboard)->modelKeys());
        $dashboard->type = 'projectproposal';
        $proposal = new ProjectProposal;
        $proposal->pp = ['eu' => 'yes'];
        $dashboard->setRelation('proposal', $proposal);
        $this->assertSame(['two'], FinancialOfficerRecipients::forDashboard($dashboard)->modelKeys());
        SettingsFoEu::query()->delete();
        $this->assertSame(['one'], FinancialOfficerRecipients::forDashboard($dashboard)->modelKeys());
    }
}
