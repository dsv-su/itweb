<?php

namespace Tests\Feature;

use App\Livewire\TravelRequestExpenses;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TravelRequestExpensesTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_allowance_updates_after_dates_and_country_are_entered(): void
    {
        $country = Country::create([
            'country' => 'Norway',
            'allowance' => 600,
            'year' => '2026',
        ]);

        Livewire::test(TravelRequestExpenses::class)
            ->call('changeStartDate', '2026-06-10')
            ->assertSet('days', 0)
            ->call('changeEndDate', '2026-06-13')
            ->assertSet('days', 3)
            ->call('selectedCountry', $country->id)
            ->assertSet('countryname', 'Norway')
            ->assertSet('daily', '600')
            ->assertSet('total', 1800);
    }
}
