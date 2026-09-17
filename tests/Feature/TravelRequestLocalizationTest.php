<?php

namespace Tests\Feature;

use App\Http\Controllers\TravelRequestController;
use Mockery\MockInterface;
use Tests\TestCase;

class TravelRequestLocalizationTest extends TestCase
{
    public function test_travel_form_respects_the_language_switcher(): void
    {
        $this->withoutMiddleware(['auth', 'dsv']);

        $this->partialMock(TravelRequestController::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andReturnUsing(fn () => response()->json([
                'locale' => app()->getLocale(),
                'placeholder' => __('Describe the purpose of your mission'),
            ]));
        });

        $this->get('/travel')->assertOk()->assertJsonPath('locale', 'sv');

        $this->from('/swe/travel')->get('/lang/en')->assertRedirect('/travel');
        $this->get('/travel')->assertOk()->assertExactJson([
            'locale' => 'en',
            'placeholder' => 'Describe the purpose of your mission',
        ]);

        $this->from('/travel')->get('/lang/sv')->assertRedirect('/swe/travel');
        $this->get('/swe/travel')->assertOk()->assertExactJson([
            'locale' => 'sv',
            'placeholder' => 'Beskriv syftet med ditt uppdrag',
        ]);
    }
}
