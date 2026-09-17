@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    @include('pp.partials.breadcrumb')
    @include('pp.partials.flashmessage')
    @include('stats.partials.tabs')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-6xl px-4 py-8 mx-auto lg:py-16">
            @include('stats.partials.controls')
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @if (count($counts))
                <div class="mt-8 space-y-8">
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @include('stats.partials.unit-chart', [
                            'heading' => 'Proposals per '.$groupLabel,
                            'unitChart' => $chart,
                            'badge' => request()->routeIs('pp.stats.committed') ? 'Sent' : 'Granted',
                            'note' => null,
                        ])
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @foreach ($budgetCharts as $currency => $budgetChart)
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Requested budget',
                                'unitChart' => $budgetChart,
                                'badge' => $currency,
                                'note' => null,
                            ])
                        @endforeach
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @foreach ($cofinancingCharts as $currency => $cofinancingChart)
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Cofinancing promised',
                                'unitChart' => $cofinancingChart,
                                'badge' => $currency,
                                'note' => null,
                            ])
                        @endforeach
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @include('stats.partials.unit-chart', [
                            'heading' => 'Committed PhD student years',
                            'unitChart' => $phdChart,
                            'badge' => 'Years',
                            'note' => null,
                        ])
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        <div class="lg:col-span-2">
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Funding agency per '.$groupLabel,
                                'unitChart' => $agencyChart,
                                'badge' => 'Proposals',
                                'note' => 'Proposal counts by funding agency. Missing agencies appear as “Unknown funding agency”.',
                            ])
                        </div>
                    </div>
                    @include('stats.partials.investigator-chart')
                    <div class="grid grid-cols-1 items-start gap-6">
                        @foreach ($investigatorBudgetCharts ?? [] as $currency => $investigatorBudgetChart)
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Requested budget per principal investigator by '.$groupLabel,
                                'unitChart' => $investigatorBudgetChart,
                                'badge' => $currency,
                                'note' => $groupLabel === 'unit'
                                    ? 'Requested DSV budgets grouped by unit. Proposals linked to multiple units contribute their full requested budget once to each unit.'
                                    : 'Requested DSV budgets grouped by the proposal’s research subject. Only investigators with a non-zero requested budget in this currency are shown.',
                            ])
                        @endforeach
                    </div>
                </div>

            @else
                <p class="mt-6 text-gray-700 dark:text-gray-300">No matching proposals for {{ $fromYear }}.</p>
            @endif
        </div>
    </section>
@endsection
