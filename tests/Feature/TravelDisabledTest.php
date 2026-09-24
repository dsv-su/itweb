<?php

namespace Tests\Feature;

use App\Http\Controllers\TravelRequestController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TravelDisabledTest extends TestCase
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
        config(['travel.enabled' => false]);
    }

    public static function disabledRoutes(): iterable
    {
        foreach (['', '/en', '/sv', '/swe'] as $prefix) {
            foreach (['/travel', '/travel/statistics', '/travel/show/missing', '/travel/review/missing', '/travelresume/missing'] as $path) {
                yield ['GET', $prefix.$path, $prefix.'/travel-disabled'];
            }
            yield ['POST', $prefix.'/travelresume/missing', $prefix.'/travel-disabled'];
        }

        foreach (['/travel', '/review/missing', '/fo_review/missing', '/travel/completed/missing'] as $path) {
            yield ['POST', $path, '/travel-disabled'];
        }

        foreach (['/travel/completed/missing/edit', '/viewpdf/missing', '/travel/pdf/missing'] as $path) {
            yield ['GET', $path, '/travel-disabled'];
        }
    }

    #[DataProvider('disabledRoutes')]
    public function test_disabled_routes_redirect_before_authentication_or_model_lookup(string $method, string $path, string $destination): void
    {
        $this->call($method, $path)->assertStatus(303)->assertRedirect($destination);
    }

    public function test_notice_is_accessible_in_both_languages(): void
    {
        $this->withoutVite();
        $this->get('/en/travel-disabled')->assertOk()
            ->assertSee('Travel requests and travel statistics are temporarily disabled.');
        $this->get('/swe/travel-disabled')->assertOk()
            ->assertSee('Reseansökningar och resestatistik är tillfälligt avstängda.');
    }

    public function test_travel_can_be_reenabled(): void
    {
        config(['travel.enabled' => true]);
        $this->withoutMiddleware(['auth', 'dsv']);
        $this->partialMock(TravelRequestController::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn(response('Travel form'));
        });

        $this->get('/travel')->assertOk()->assertSee('Travel form');
    }
}
