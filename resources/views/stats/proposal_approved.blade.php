@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    @include('pp.partials.breadcrumb')
    @include('pp.partials.flashmessage')
    @include('stats.partials.tabs')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-6xl px-4 py-8 mx-auto lg:py-16">
            @include('stats.partials.heading', ['title' => 'Granted Proposals'])

            <div class="mt-8 space-y-8">
                @foreach (['sek', 'eur', 'usd'] as $currency)
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @include('stats.partials.unit-chart', [
                            'heading' => 'Granted amount',
                            'unitChart' => $chart['researchsubject_granted_'.$currency],
                            'badge' => strtoupper($currency),
                            'note' => null,
                        ])
                        @include('stats.partials.unit-chart', [
                            'heading' => 'Cofinancing promised',
                            'unitChart' => $chart['researchsubject_promised_'.$currency],
                            'badge' => strtoupper($currency),
                            'note' => null,
                        ])
                    </div>
                @endforeach
                @include('stats.partials.investigator-chart')
            </div>
        </div>
    </section>
@endsection
