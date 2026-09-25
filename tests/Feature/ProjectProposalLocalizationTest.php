<?php

namespace Tests\Feature;

use App\Http\Controllers\ProposalHomeController;
use Mockery\MockInterface;
use Tests\TestCase;

class ProjectProposalLocalizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(['auth', 'dsv']);
        $this->partialMock(ProposalHomeController::class, function (MockInterface $mock) {
            $mock->shouldReceive('pp')->andReturnUsing(fn () => response()->json([
                'locale' => app()->getLocale(),
                'settings' => __('Settings'),
            ]));
        });
    }

    public function test_project_proposals_default_to_english(): void
    {
        foreach (['/projectproposals', '/projectproposals/my'] as $url) {
            $this->flushSession();
            $this->get($url)->assertOk()
                ->assertExactJson(['locale' => 'en', 'settings' => 'Settings'])
                ->assertSessionHas('locale', 'en');
        }
    }

    public function test_project_proposals_preserve_a_saved_language_preference(): void
    {
        $this->withSession(['locale' => 'sv'])->get('/projectproposals')->assertOk()
            ->assertExactJson(['locale' => 'sv', 'settings' => 'Inställningar']);
    }
}
