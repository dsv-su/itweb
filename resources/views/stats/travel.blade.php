@extends('layouts.app')

@section('page-navigation')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
@endsection

@section('content')
    @php
        $number = fn ($value) => number_format($value, 0, app()->getLocale() === 'sv' ? ',' : '.', app()->getLocale() === 'sv' ? ' ' : ',');
        $focus = 'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:focus-visible:outline-blue-300';
        $panel = 'min-w-0 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800';
    @endphp
    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-white">
        <div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ __('Travel statistics') }}</h1>
                    <p class="mt-3 max-w-2xl text-gray-600 dark:text-gray-300">{{ __('Approved travel requests by departure year. Costs are estimates in SEK, not actual expenses.') }}</p>
                </div>
                <form method="GET" class="flex max-w-full flex-wrap items-end gap-3">
                    <div>
                        <label for="travel-year" class="mb-2 block text-sm font-medium">{{ __('Departure year') }}</label>
                        <select id="travel-year" name="year" class="min-h-11 rounded-lg border border-gray-500 bg-white text-gray-900 dark:border-gray-400 dark:bg-gray-800 dark:text-white {{ $focus }}">
                            @foreach($years as $availableYear)
                                <option value="{{ $availableYear }}" @selected($availableYear === $year)>{{ $availableYear }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="min-h-11 rounded-lg bg-blue-700 px-4 py-2.5 text-white hover:bg-blue-800 {{ $focus }}">{{ __('Show statistics') }}</button>
                </form>
            </div>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['Approved trips' => $number($count), 'Travel days' => $number($days), 'Estimated total cost' => $number($total).' SEK', 'Average cost per trip' => $number($count ? $total / $count : 0).' SEK'] as $label => $value)
                    <div class="{{ $panel }}">
                        <dt class="text-sm text-gray-600 dark:text-gray-300">{{ __($label) }}</dt>
                        <dd class="mt-2 break-words text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            @if($count === 0)
                <p class="{{ $panel }}">{{ __('No approved travel requests for this departure year.') }}</p>
            @else
                <div class="grid gap-6 lg:grid-cols-2">
                    <section class="{{ $panel }}" aria-labelledby="trips-by-month">
                        <h2 id="trips-by-month" class="mb-5 text-xl font-semibold">{{ __('Trips by month') }}</h2>
                        <dl class="space-y-3">
                            @foreach($monthly as $month)
                                <div class="flex items-center gap-3">
                                    <dt class="w-12 shrink-0 break-words text-sm">{{ $month['label'] }}</dt>
                                    <dd class="flex min-w-0 flex-1 items-center gap-3">
                                        <div class="h-3 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700" aria-hidden="true">
                                            <div class="h-full rounded-full bg-blue-600 dark:bg-blue-400" style="width: {{ $month['count'] / max(1, $monthly->max('count')) * 100 }}%"></div>
                                        </div>
                                        <span class="shrink-0 text-right text-sm tabular-nums">{{ $number($month['count']) }}</span>
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>
                    <section class="{{ $panel }}" aria-labelledby="cost-breakdown">
                        <h2 id="cost-breakdown" class="mb-5 text-xl font-semibold">{{ __('Estimated cost breakdown') }}</h2>
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($costs as $label => $amount)
                                <div class="flex flex-wrap justify-between gap-x-4 gap-y-1 py-4">
                                    <dt>{{ __($label) }}</dt>
                                    <dd class="font-semibold tabular-nums">{{ $number($amount) }} SEK</dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>
                </div>
                <section class="{{ $panel }}">
                    <h2 id="travel-destinations" class="mb-5 text-xl font-semibold">{{ __('Travel destinations') }}</h2>
                    <div role="region" aria-labelledby="travel-destinations" tabindex="0" class="overflow-x-auto rounded-sm {{ $focus }}">
                        <table aria-labelledby="travel-destinations" class="w-full text-left text-sm">
                            <thead class="border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 pr-4">{{ __('Country') }}</th>
                                    <th scope="col" class="px-4 py-3 text-right">{{ __('Approved trips') }}</th>
                                    <th scope="col" class="py-3 pl-4 text-right">{{ __('Estimated total cost') }} (SEK)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($destinations as $country => $destination)
                                    <tr>
                                        <th scope="row" class="py-3 pr-4 font-medium">{{ $country ?: __('Unspecified') }}</th>
                                        <td class="px-4 py-3 text-right tabular-nums">{{ $number($destination['count']) }}</td>
                                        <td class="py-3 pl-4 text-right tabular-nums">{{ $number($destination['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
