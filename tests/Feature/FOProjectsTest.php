<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class FOProjectsTest extends TestCase
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
        $this->withoutMiddleware(ValidateCsrfToken::class);
        (require database_path('migrations/2023_10_23_092059_create_projects_table.php'))->up();
    }

    private function officer(bool $allowed = true): void
    {
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 'officer';
        $user->shouldReceive('isFO')->andReturn($allowed);
        $this->actingAs($user);
    }

    private function data(string $number = '00123'): array
    {
        return ['project' => $number, 'description' => 'Research', 'projectleader' => 'Leader', 'status' => 'Active'];
    }

    private function spreadsheet(array $rows): UploadedFile
    {
        $book = new Spreadsheet;
        $book->getActiveSheet()->fromArray($rows);
        $path = tempnam(sys_get_temp_dir(), 'projects-test-');
        (new Xlsx($book))->save($path);
        $contents = file_get_contents($path);
        unlink($path);
        $book->disconnectWorksheets();

        return UploadedFile::fake()->createWithContent('projects.xlsx', $contents);
    }

    public function test_officer_can_create_and_edit_and_duplicate_numbers_are_rejected(): void
    {
        $this->officer();
        $this->post(route('fo.projects.store'), $this->data())->assertRedirect(route('fo.projects'));
        $project = Project::firstOrFail();
        $this->put(route('fo.projects.update', $project), array_replace($this->data(), ['description' => 'Updated']))->assertRedirect();
        $this->assertSame('Updated', $project->fresh()->description);
        $this->postJson(route('fo.projects.store'), $this->data())->assertUnprocessable();
        $this->assertDatabaseCount('projects', 1);
        $this->putJson(route('fo.projects.update', $project), ['project' => 'new'])->assertUnprocessable();
    }

    public function test_leader_can_change_when_existing_project_numbers_are_duplicated(): void
    {
        $this->officer();
        $project = Project::create($this->data());
        $duplicate = Project::create($this->data());

        $this->put(route('fo.projects.update', $project), array_replace($this->data(), ['projectleader' => 'New leader']))
            ->assertRedirect(route('fo.projects'))
            ->assertSessionHasNoErrors();

        $this->assertSame('New leader', $project->fresh()->projectleader);
        $this->assertSame('Leader', $duplicate->fresh()->projectleader);
        $this->assertSame('00123', $project->fresh()->project);
    }

    public function test_changing_project_number_to_an_existing_number_is_rejected(): void
    {
        $this->officer();
        $project = Project::create($this->data());
        Project::create($this->data('other'));

        $this->putJson(route('fo.projects.update', $project), $this->data('other'))
            ->assertUnprocessable()->assertJsonValidationErrors('project');
        $this->assertSame('00123', $project->fresh()->project);
    }

    public function test_empty_status_does_not_block_a_leader_change(): void
    {
        $this->officer();
        $project = Project::create(array_replace($this->data(), ['status' => '']));
        $this->put(route('fo.projects.update', $project), array_replace($this->data(), [
            'projectleader' => 'New leader', 'status' => '',
        ]))->assertRedirect(route('fo.projects'))->assertSessionHasNoErrors();
        $this->assertSame('New leader', $project->fresh()->projectleader);
        $this->assertSame('', $project->fresh()->status);

        $this->post(route('fo.projects.store'), array_replace($this->data('new'), ['status' => '']))
            ->assertRedirect(route('fo.projects'))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('projects', ['project' => 'new', 'status' => '']);
    }

    public function test_officer_can_delete_only_the_selected_project(): void
    {
        $this->officer();
        $project = Project::create($this->data());
        $duplicate = Project::create($this->data());

        $this->delete(route('fo.projects.destroy', $project))
            ->assertRedirect(route('fo.projects'))
            ->assertSessionHas('status', 'Projektet har tagits bort.');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('projects', ['id' => $duplicate->id]);
        $this->delete(route('fo.projects.destroy', $project))->assertNotFound();
        $this->assertDatabaseCount('projects', 1);
    }

    public function test_project_view_compiles(): void
    {
        $compiled = app('blade.compiler')->compileString(file_get_contents(resource_path('views/requests/fo/projects.blade.php')));
        $path = tempnam(sys_get_temp_dir(), 'project-view-');
        file_put_contents($path, $compiled);
        exec(PHP_BINARY.' -l '.escapeshellarg($path).' 2>&1', $output, $status);
        unlink($path);
        $this->assertSame(0, $status, implode("\n", $output));
    }

    public function test_all_project_routes_require_a_financial_officer(): void
    {
        $project = Project::create($this->data());
        foreach ([false, true] as $loggedIn) {
            if ($loggedIn) {
                $this->officer(false);
            }
            foreach ([['GET', route('fo.projects')], ['POST', route('fo.projects.store')], ['PUT', route('fo.projects.update', $project)], ['DELETE', route('fo.projects.destroy', $project)], ['POST', route('fo.projects.import')]] as [$method, $url]) {
                $this->json($method, $url)->assertStatus($loggedIn ? 403 : 401);
            }
        }
    }

    public function test_excel_refresh_updates_in_place_adds_rows_and_preserves_missing_projects(): void
    {
        $this->officer();
        $existing = Project::create($this->data());
        Project::create($this->data('keep'));
        $rows = [
            ['Projekt', 'Projektbenämning', 'Projektledare', 'Status'],
            ['00123', 'Updated', 'New leader', 'Closed'],
            ['00456', 'New project', 'Leader', 'Active'],
        ];
        for ($i = 0; $i < 2; $i++) {
            $this->post(route('fo.projects.import'), ['file' => $this->spreadsheet($rows)])
                ->assertRedirect(route('fo.projects'))->assertSessionHasNoErrors();
        }
        $this->assertSame('Updated', $existing->fresh()->description);
        $this->assertDatabaseHas('projects', ['project' => '00456']);
        $this->assertDatabaseCount('projects', 3);
    }

    public function test_invalid_imports_leave_existing_projects_unchanged(): void
    {
        $this->officer();
        $existing = Project::create($this->data());
        foreach ([
            [['Projekt', 'Projektbenämning', 'Projektledare', 'Status'], ['00123', 'Changed', 'Leader', 'Active'], ['new', null, 'Leader', 'Active']],
            [['Projekt', 'Projektbenämning', 'Projektledare', 'Status'], ['00123', 'Changed', 'Leader', 'Active'], ['00123', 'Duplicate', 'Leader', 'Active']],
            [['Wrong headings'], ['value']],
            [['Projekt', 'Projektbenämning', 'Projektledare', 'Status']],
        ] as $rows) {
            $this->post(route('fo.projects.import'), ['file' => $this->spreadsheet($rows)])->assertSessionHasErrors('file');
            $this->assertSame('Research', $existing->fresh()->description);
            $this->assertDatabaseCount('projects', 1);
        }
        $this->postJson(route('fo.projects.import'), ['file' => UploadedFile::fake()->create('invalid.txt')])->assertUnprocessable();
    }
}
