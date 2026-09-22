<?php

namespace Tests\Feature;

use App\Http\Controllers\FOController;
use App\Http\Middleware\EnsureUserIsFO;
use Mockery\MockInterface;
use Tests\TestCase;

class FORequestLocalizationTest extends TestCase
{
    public function test_swedish_content_updates_the_language_before_visiting_settings(): void
    {
        $this->withoutMiddleware([
            EnsureUserIsFO::class,
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\DSVStaffEntitlement::class,
        ]);

        \Statamic\Facades\Data::shouldReceive('findByRequestUrl')->once()->with(url('/swe'))
            ->andReturnUsing(fn () => response('Swedish content'));

        $this->partialMock(FOController::class, function (MockInterface $mock) {
            $mock->shouldReceive('settings')->andReturnUsing(fn () => response()->json([
                'locale' => app()->getLocale(),
                'site_locale' => \Statamic\Facades\Site::current()->shortLocale(),
            ]));
        });

        $this->withSession(['locale' => 'en'])->get('/swe')
            ->assertOk()->assertSessionHas('locale', 'sv');
        $this->get(route('settings'))->assertOk()
            ->assertExactJson(['locale' => 'sv', 'site_locale' => 'sv']);
    }

    public function test_language_switcher_keeps_application_routes_without_a_language_prefix(): void
    {
        foreach (['/settings', '/fo/projects', '/vice-settings'] as $path) {
            $this->from($path)->get('/lang/sv')->assertRedirect($path)
                ->assertSessionHas('locale', 'sv');
            $this->from($path)->get('/lang/en')->assertRedirect($path)
                ->assertSessionHas('locale', 'en');
        }
    }

    public function test_language_switcher_handles_prefixed_routes_and_preserves_queries(): void
    {
        $this->from('/swe/newslist/news?page=2')->get('/lang/en')
            ->assertRedirect('/en/newslist/news?page=2');
        $this->from('/en/list?page=2')->get('/lang/swe')
            ->assertRedirect('/swe/list?page=2')->assertSessionHas('locale', 'sv');
    }

    public function test_settings_defaults_to_swedish_and_preserves_the_selected_navigation_language(): void
    {
        $this->withoutMiddleware(EnsureUserIsFO::class);

        $this->partialMock(FOController::class, function (MockInterface $mock) {
            $mock->shouldReceive('settings')->andReturnUsing(fn () => response()->json([
                'locale' => app()->getLocale(),
                'site_locale' => \Statamic\Facades\Site::current()->shortLocale(),
            ]));
        });

        $this->get(route('settings'))
            ->assertOk()
            ->assertExactJson(['locale' => 'sv', 'site_locale' => 'sv']);

        foreach (['en', 'sv'] as $locale) {
            $this->withSession(['locale' => $locale])->get(route('settings'))
                ->assertOk()
                ->assertExactJson(['locale' => $locale, 'site_locale' => $locale]);

            $this->get(route('settings'))
                ->assertOk()
                ->assertExactJson(['locale' => $locale, 'site_locale' => $locale]);
        }
    }

    public function test_list_preserves_the_selected_language_on_subsequent_visits(): void
    {
        $this->partialMock(FOController::class, function (MockInterface $mock) {
            $mock->shouldReceive('list')->andReturnUsing(fn () => response()->json([
                'locale' => app()->getLocale(),
                'site_locale' => \Statamic\Facades\Site::current()->shortLocale(),
            ]));
        });

        foreach (['swe' => 'sv', 'en' => 'en', 'sv' => 'sv'] as $prefix => $locale) {
            $this->withSession(['locale' => $locale === 'sv' ? 'en' : 'sv'])
                ->get(route('request-list.localized', ['lang' => $prefix]))
                ->assertOk()
                ->assertJsonPath('locale', $locale)
                ->assertJsonPath('site_locale', $locale)
                ->assertSessionHas('locale', $locale);

            $this->get('/list')->assertOk()->assertJsonPath('locale', $locale);
        }
    }

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
