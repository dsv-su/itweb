<?php

namespace Tests\Feature;

use App\Livewire\Select2\CountrySelect2;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CountryLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_country_names_follow_the_locale_and_fall_back_to_the_source_name(): void
    {
        $country = new Country(['country' => 'Tyskland']);

        app()->setLocale('en');
        $this->assertSame('Germany', $country->localized_name);
        $this->assertSame('Other countries and territories', (new Country(['country' => 'Övriga länder och områden']))->localized_name);
        $this->assertSame('New source entry', (new Country(['country' => 'New source entry']))->localized_name);

        app()->setLocale('sv');
        $this->assertSame('Tyskland', $country->localized_name);
    }

    public function test_english_search_and_selection_preserve_the_swedish_value(): void
    {
        app()->setLocale('en');
        $country = Country::create(['country' => 'Tyskland', 'allowance' => '800', 'year' => '2026']);
        Country::create(['country' => 'Frankrike', 'allowance' => '900', 'year' => '2026']);

        $component = Livewire::test(CountrySelect2::class)
            ->set('search', 'germany')
            ->assertSee('Germany')
            ->assertDontSee('France');

        $this->assertSame([$country->id], $component->get('options')->modelKeys());

        $component->call('save')
            ->assertSet('Country.country', 'Tyskland')
            ->assertDispatched('selectedCountry', $country->id)
            ->assertSeeHtml('name="country" value="Tyskland"');

        Livewire::test(CountrySelect2::class, ['country' => 'Tyskland'])
            ->assertSet('Country.id', $country->id)
            ->assertSee('Germany');
    }

    public function test_swedish_search_uses_swedish_names(): void
    {
        app()->setLocale('sv');
        $country = Country::create(['country' => 'Tyskland', 'allowance' => '800', 'year' => '2026']);

        $component = Livewire::test(CountrySelect2::class)
            ->set('search', 'tysk')
            ->assertSee('Tyskland');

        $this->assertSame([$country->id], $component->get('options')->modelKeys());
    }
}
