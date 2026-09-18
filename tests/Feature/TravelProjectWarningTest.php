<?php

namespace Tests\Feature;

use App\Livewire\Select2\ProjectSelect2;
use App\Models\Project;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class TravelProjectWarningTest extends TestCase
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
        app()->setLocale('en');
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function test_warning_tracks_selection_search_and_clearing(): void
    {
        $project = Project::create(['project' => '12345', 'description' => 'Travel project']);

        Livewire::test(ProjectSelect2::class, ['showProjectWarning' => true])
            ->assertSee('No project selected')
            ->call('select', $project->id)
            ->assertDontSee('No project selected')
            ->call('clear')
            ->assertSee('No project selected')
            ->set('search', '12345')
            ->call('save')
            ->assertDontSee('No project selected');
    }

    public function test_existing_project_does_not_show_warning(): void
    {
        Project::create(['project' => '12345']);

        Livewire::test(ProjectSelect2::class, ['id' => '12345', 'showProjectWarning' => true])
            ->assertDontSee('No project selected');
    }

    public function test_unknown_project_shows_warning_and_review_usage_is_unchanged(): void
    {
        Livewire::test(ProjectSelect2::class, ['id' => 'missing', 'showProjectWarning' => true])
            ->assertSee('No project selected');

        Livewire::test(ProjectSelect2::class)->assertDontSee('No project selected');
    }
}
