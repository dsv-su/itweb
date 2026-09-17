<?php

namespace Tests\Feature;

use App\Http\Controllers\FOController;
use App\Http\Middleware\EnsureUserIsFO;
use Mockery\MockInterface;
use Tests\TestCase;

class FORequestLocalizationTest extends TestCase
{
    public function test_localized_show_preserves_the_request_id_and_sets_the_locale(): void
    {
        $this->withoutMiddleware(EnsureUserIsFO::class);

        $this->partialMock(FOController::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')->with('123')->times(4)
                ->andReturnUsing(fn ($id) => response()->json([
                    'id' => $id,
                    'locale' => app()->getLocale(),
                ]));
        });

        foreach (['swe' => 'sv', 'sv' => 'sv', 'en' => 'en'] as $prefix => $locale) {
            $this->get("/{$prefix}/show/123")
                ->assertOk()
                ->assertExactJson(['id' => '123', 'locale' => $locale]);
        }

        $this->withSession(['locale' => 'en'])->get('/show/123')
            ->assertOk()
            ->assertExactJson(['id' => '123', 'locale' => 'en']);
    }
}
