<?php

namespace Tests\Feature;

use App\Livewire\Pp\Fo\Assign;
use App\Mail\NotifyAssignedFO;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ProposalFoSwitchTest extends TestCase
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
            $table->string('email')->nullable();
        });
        foreach (['role_user' => 'role_id', 'group_user' => 'group_id'] as $name => $column) {
            Schema::create($name, function (Blueprint $table) use ($column) {
                $table->string('user_id');
                $table->string($column);
            });
        }
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->boolean('reminder')->default(true);
            $table->timestamps();
        });
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            foreach (['request_id', 'type', 'fo_id', 'state'] as $field) {
                $table->string($field);
            }
            $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 'old', 'name' => 'Previous Officer'],
            ['id' => 'new', 'name' => 'New Officer'],
            ['id' => 'outsider', 'name' => 'Other User'],
        ]);
        DB::table('role_user')->insert(['user_id' => 'old', 'role_id' => 'financial_officer']);
        DB::table('group_user')->insert(['user_id' => 'new', 'group_id' => 'ekonomi']);
        DB::table('project_proposals')->insert(['id' => 'trip']);
        DB::table('dashboards')->insert(['id' => 1, 'request_id' => 'trip', 'type' => 'projectproposal', 'fo_id' => 'old', 'state' => 'fo_approved']);
        DB::table('group_user')->insert(['user_id' => 'old', 'group_id' => 'ekonomi']);
        DB::table('users')->where('id', 'new')->update(['email' => 'new@example.test']);
        Mail::fake();
        $this->actingAs(User::find('old'));
    }

    private function assignmentComponent(): Assign
    {
        $component = new Assign;
        $component->proposal = ProjectProposal::findOrFail('trip');

        return $component;
    }

    public function test_selection_and_cancel_do_not_save_but_save_updates_assignment(): void
    {
        $component = $this->assignmentComponent();
        $component->switchFo();
        $component->selectedFoId = 'new';
        $component->cancelFoSwitch();
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'old']);
        $component->switchFo();
        $component->selectedFoId = 'new';
        $component->saveFo();
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'new', 'state' => 'fo_approved']);
        $this->assertFalse($component->switchingFo);
        Mail::assertNothingSent();
    }

    public function test_review_assignment_sends_mail_only_when_changed(): void
    {
        DB::table('dashboards')->where('id', 1)->update(['state' => 'head_approved']);
        $component = $this->assignmentComponent();
        $component->switchFo();
        $component->selectedFoId = 'new';
        $component->saveFo();
        Mail::assertSent(NotifyAssignedFO::class, fn ($mail) => $mail->hasTo('new@example.test'));
        $component->switchFo();
        $component->saveFo();
        Mail::assertSentCount(1);
    }

    public function test_non_finance_user_cannot_save(): void
    {
        $this->actingAs(User::find('outsider'));
        $component = $this->assignmentComponent();
        $component->selectedFoId = 'new';
        try {
            $component->saveFo();
            $this->fail('Unauthorized assignment accepted.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'old']);
    }

    public function test_selected_officer_must_belong_to_finance(): void
    {
        $component = $this->assignmentComponent();
        $component->switchFo();
        $component->selectedFoId = 'outsider';
        try {
            $component->saveFo();
            $this->fail('Invalid assignment accepted.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('selectedFoId', $e->errors());
        }
        $this->assertDatabaseHas('dashboards', ['id' => 1, 'fo_id' => 'old']);
        Mail::assertNothingSent();
    }
}
