<?php

namespace Tests\Feature;

use Statamic\Facades\Data;
use Tests\TestCase;

class HomeLocalizationTest extends TestCase
{
    public function test_new_visitors_default_to_swedish_and_keep_the_query_string(): void
    {
        $this->withoutMiddleware([
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\DSVStaffEntitlement::class,
        ]);

        $this->get('/?source=home')
            ->assertRedirect('/swe?source=home')
            ->assertSessionHas('locale', 'sv');
    }

    public function test_homepage_language_can_be_switched_to_english_and_back(): void
    {
        $this->withoutMiddleware([
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\DSVStaffEntitlement::class,
        ]);

        Data::shouldReceive('findByRequestUrl')->once()->with(url('/'))
            ->andReturnUsing(fn () => response('English homepage: ' . app()->getLocale()));

        $this->get('/')->assertRedirect('/swe');
        $this->from('/swe?source=home')->get('/lang/en')
            ->assertRedirect('/?source=home&lang=en')
            ->assertSessionHas('locale', 'en');

        $this->get('/?source=home&lang=en')->assertOk()
            ->assertSee('English homepage: en')
            ->assertSessionHas('locale', 'en');

        $this->from('/?lang=en')->get('/lang/sv')->assertRedirect('/swe');
        $this->get('/')->assertRedirect('/swe')->assertSessionHas('locale', 'sv');
    }

    public function test_first_english_switch_overrides_a_stale_swedish_session(): void
    {
        $this->withoutMiddleware([
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\DSVStaffEntitlement::class,
        ]);

        Data::shouldReceive('findByRequestUrl')->once()->with(url('/'))
            ->andReturnUsing(fn () => response('Homepage: ' . app()->getLocale()));

        $response = $this->from('/swe/')->get('/lang/en');
        $response->assertRedirect('/?lang=en');

        $this->withSession(['locale' => 'sv', 'localisation' => 'sv'])
            ->get($response->headers->get('Location'))
            ->assertOk()
            ->assertSee('Homepage: en')
            ->assertSessionHas('locale', 'en')
            ->assertSessionHas('localisation', 'en');
    }
}
