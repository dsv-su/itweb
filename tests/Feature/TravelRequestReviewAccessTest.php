<?php

namespace Tests\Feature;

use App\Http\Controllers\ReviewController;
use App\Http\Middleware\DSVStaffEntitlement;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery\MockInterface;
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

    public function test_direct_link_only_allows_the_reviewer_for_the_current_stage(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'state', 'manager_id', 'head_id', 'fo_id', 'vice_id'] as $column) {
                $table->string($column)->nullable();
            }
        });

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

            foreach (['manager', 'head', 'fo', 'unrelated'] as $userId) {
                $user = new User;
                $user->id = $userId;
                $this->actingAs($user);

                foreach (['/travel/review/42', '/swe/travel/review/42'] as $url) {
                    $this->get($url)->assertStatus($userId === $reviewer ? 200 : 403);
                }
            }
        }
    }
}
