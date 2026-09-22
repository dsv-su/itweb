<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelStatisticsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate(['year' => ['nullable', 'integer', 'between:1970,9999']]);
        $year = (int) ($validated['year'] ?? now()->year);
        $approved = TravelRequest::query()->whereHas('dashboard', fn ($query) => $query->where('state', 'fo_approved'));
        $years = (clone $approved)->whereNotNull('departure')->pluck('departure')
            ->map(fn ($date) => CarbonImmutable::createFromTimestamp($date, config('app.timezone'))->year)
            ->push($year, now()->year)->unique()->sortDesc()->values();
        $start = CarbonImmutable::create($year, 1, 1, 0, 0, 0, config('app.timezone'));
        $trips = $approved->where('departure', '>=', $start->timestamp)
            ->where('departure', '<', $start->addYear()->timestamp)
            ->get(['departure', 'country', 'days', 'flight', 'hotel', 'daily', 'conference', 'other_costs', 'total']);
        $monthly = collect(range(1, 12))->map(function ($month) use ($trips, $start) {
            return [
                'label' => $start->month($month)->locale(app()->getLocale())->translatedFormat('M'),
                'count' => $trips->filter(fn ($trip) => CarbonImmutable::createFromTimestamp($trip->departure, config('app.timezone'))->month === $month)->count(),
            ];
        });
        $destinations = $trips->groupBy('country')->map(fn ($group) => [
            'count' => $group->count(), 'total' => $group->sum('total'),
        ])->sortByDesc('count');
        $costs = collect([
            'Flights and train' => $trips->sum('flight'),
            'Accommodation' => $trips->sum('hotel'),
            'Daily allowances' => $trips->sum(fn ($trip) => $trip->daily * $trip->days),
            'Conference fees' => $trips->sum('conference'),
            'Other costs' => $trips->sum('other_costs'),
        ]);

        return view('stats.travel', [
            'title' => __('Travel statistics'),
            'year' => $year, 'years' => $years, 'monthly' => $monthly,
            'destinations' => $destinations, 'costs' => $costs,
            'count' => $trips->count(), 'days' => $trips->sum('days'),
            'total' => $trips->sum('total'),
        ]);
    }
}
