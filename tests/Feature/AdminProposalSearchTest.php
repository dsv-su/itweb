<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminProposalSearchTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function () use ($app) {
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

        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('status_stage3');
            $table->unsignedBigInteger('created');
            $table->json('pp');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
        });
        DB::table('users')->insert(['id' => 1, 'name' => 'Alice Researcher']);
    }

    private function proposal(string $id, array $overrides = []): void
    {
        DB::table('project_proposals')->insert(array_merge([
            'id' => $id,
            'user_id' => 1,
            'name' => 'Climate study',
            'status_stage3' => 'submitted',
            'created' => 100,
            'pp' => json_encode(['research_area' => 'Computer science', 'funding_organization' => 'Research council']),
        ], $overrides));
    }

    public function test_search_matches_each_visible_field_and_excludes_pending_proposals(): void
    {
        $this->proposal('submitted');
        $this->proposal('pending', ['status_stage3' => 'pending']);

        foreach (['Climate', 'Alice', 'Computer', 'council'] as $term) {
            $data = (new AdminController)->pp(Request::create('/admin', 'GET', ['search' => ' '.$term.' ']))->data();
            $this->assertSame($term, $data['search']);
            $this->assertSame(['submitted'], $data['proposals']->pluck('id')->all());
            $this->assertTrue($data['proposals']->first()->relationLoaded('submitter'));
            $this->assertTrue($data['proposals']->first()->relationLoaded('dashboard'));
        }
        $data = (new AdminController)->pp(Request::create('/admin', 'GET', ['search' => 'unmatched']))->data();
        $this->assertSame(0, $data['proposals']->total());
    }

    public function test_search_covers_all_pages_and_is_preserved_in_pagination(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->proposal((string) $i, ['created' => $i]);
        }
        $this->proposal('other', ['name' => 'Unrelated project']);
        $data = (new AdminController)->pp(Request::create('/admin', 'GET', ['search' => 'Climate']))->data();
        $this->assertSame(12, $data['proposals']->total());
        $this->assertCount(10, $data['proposals']);
        $this->assertSame('12', $data['proposals']->first()->id);
        $this->assertStringContainsString('search=Climate', $data['proposals']->nextPageUrl());

        $data = (new AdminController)->pp(Request::create('/admin', 'GET', ['search' => '   ']))->data();
        $this->assertSame(13, $data['proposals']->total());
    }
}
