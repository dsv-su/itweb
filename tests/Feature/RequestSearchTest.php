<?php

namespace Tests\Feature;

use App\Livewire\RequestSearch;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class RequestSearchTest extends TestCase
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
            $table->string('email');
        });
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('project');
            $table->string('country');
            $table->string('purpose');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'state', 'name'] as $field) {
                $table->string($field);
            }
            $table->string('user_id')->nullable();
            $table->string('fo_id')->nullable();
            $table->integer('created')->default(1700000000);
        });
        for ($id = 1; $id <= 12; $id++) {
            DB::table('dashboards')->insert([
                'request_id' => 'trip-'.$id, 'type' => 'travelrequest',
                'state' => 'fo_approved', 'name' => 'Completed '.$id, 'created' => 1700000000 + $id,
            ]);
        }
        DB::table('dashboards')->insert([
            ['request_id' => 'pending', 'type' => 'travelrequest', 'state' => 'head_approved', 'name' => 'Pending trip'],
            ['request_id' => 'proposal', 'type' => 'projectproposal', 'state' => 'head_approved', 'name' => 'Pending proposal'],
        ]);
    }

    public function test_pending_reviews_appear_first_and_are_excluded_from_other_requests(): void
    {
        Livewire::test(RequestSearch::class)
            ->assertSeeInOrder(['Inväntar granskning av ekonomihandläggare', 'Pending trip', 'Övriga ärenden', 'Completed 12'])
            ->assertViewHas('awaitingFoReview', fn ($rows) => $rows->total() === 1)
            ->assertViewHas('dashboards', fn ($rows) => $rows->total() === 12 && $rows->every(fn ($row) => (string) $row->state !== 'head_approved'))
            ->call('gotoPage', 2)
            ->assertSee('Pending trip')
            ->assertSee('Completed 2')
            ->set('searchTerm', 'Pending')
            ->assertViewHas('dashboards', fn ($rows) => $rows->total() === 0 && $rows->currentPage() === 1)
            ->assertViewHas('awaitingFoReview', fn ($rows) => $rows->total() === 1)
            ->set('searchTerm', 'Completed')
            ->assertSee('Inga ärenden som inväntar granskning av ekonomihandläggare matchar din sökning.')
            ->assertDontSee('Pending trip');
    }

    public function test_project_proposals_remain_in_one_list_and_filters_reset_both_pages(): void
    {
        Livewire::test(RequestSearch::class)
            ->call('gotoPage', 2, 'foReviewPage')
            ->set('searchTerm', 'Pending')
            ->assertSet('paginators.foReviewPage', 1)
            ->call('gotoPage', 2, 'foReviewPage')
            ->set('requestType', 'projectproposal')
            ->assertSet('paginators.foReviewPage', 1)
            ->assertViewHas('awaitingFoReview', null)
            ->assertSee('Pending proposal')
            ->assertDontSee('Inväntar granskning av ekonomihandläggare')
            ->assertDontSee('Pending trip');
    }

    public function test_sections_sort_by_creation_date_before_id_and_default_to_swedish(): void
    {
        $this->assertSame('sv', app()->getLocale());
        DB::table('dashboards')->insert([
            ['request_id' => 'older', 'type' => 'travelrequest', 'state' => 'head_approved', 'name' => 'Older pending', 'created' => 1600000000],
            ['request_id' => 'newer', 'type' => 'travelrequest', 'state' => 'head_approved', 'name' => 'Newer pending', 'created' => 1800000000],
        ]);
        DB::table('dashboards')->where('request_id', 'trip-1')->update(['created' => 1900000000]);

        Livewire::test(RequestSearch::class)
            ->assertSeeInOrder(['Inväntar granskning av ekonomihandläggare', 'Newer pending', 'Pending trip', 'Older pending', 'Övriga ärenden'])
            ->assertViewHas('dashboards', fn ($rows) => $rows->pluck('request_id')->take(3)->all() === ['trip-1', 'trip-12', 'trip-11'])
            ->assertSee('Ekonom');
    }
}
