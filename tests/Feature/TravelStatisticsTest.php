<?php

namespace Tests\Feature;

use App\Http\Controllers\TravelStatisticsController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TravelStatisticsTest extends TestCase
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
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->string('id');
            $table->string('country');
            foreach (['departure', 'days', 'flight', 'hotel', 'daily', 'conference', 'other_costs', 'total'] as $column) {
                $table->integer($column)->nullable();
            }
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('type');
            $table->string('state');
        });
    }

    public function test_statistics_only_include_final_approvals_in_the_departure_year(): void
    {
        foreach ([['a', 'fo_approved', '2026-01-01'], ['b', 'fo_approved', '2026-12-31'], ['c', 'submitted', '2026-06-01'], ['d', 'head_approved', '2026-06-01'], ['e', 'fo_approved', '2025-12-31'], ['f', 'fo_approved', '2027-01-01']] as [$id, $state, $date]) {
            DB::table('travel_requests')->insert([
                'id' => $id, 'country' => 'Sweden', 'departure' => strtotime($date),
                'days' => 3, 'daily' => 100, 'flight' => 400, 'hotel' => 200,
                'conference' => 50, 'other_costs' => 50, 'total' => 1000,
            ]);
            DB::table('dashboards')->insert(['request_id' => $id, 'type' => 'travelrequest', 'state' => $state]);
        }
        $data = (new TravelStatisticsController)(Request::create('/travel/statistics', 'GET', ['year' => 2026]))->getData();
        $this->assertSame(2, $data['count']);
        $this->assertEquals(2000, $data['total']);
        $this->assertEquals(600, $data['costs']['Daily allowances']);
        $this->assertSame(1, $data['monthly'][0]['count']);
        $this->assertSame(1, $data['monthly'][11]['count']);
        $this->assertSame(2, $data['destinations']['Sweden']['count']);
        $this->assertContains(2025, $data['years']);
    }

    public function test_empty_year_and_translations(): void
    {
        app()->setLocale('sv');
        $view = (new TravelStatisticsController)(Request::create('/travel/statistics', 'GET', ['year' => 2026]));
        $this->assertSame(0, $view->getData()['count']);
        $this->assertSame('Resestatistik', $view->getData()['title']);
        $this->assertSame('Resestatistik', __('Travel stats'));
        app()->setLocale('en');
        $this->assertSame('Travel stats', __('Travel stats'));
    }

    public function test_statistics_routes_require_authentication(): void
    {
        $this->get('/travel/statistics')->assertRedirect();
        $this->get('/swe/travel/statistics')->assertRedirect();
    }
}
