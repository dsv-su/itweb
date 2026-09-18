@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    @include('pp.partials.breadcrumb')
    @include('pp.partials.flashmessage')
    @include('stats.partials.tabs')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8">
            @include('stats.partials.heading', ['title' => 'Committed Proposals'])

            <div class="mt-8 space-y-8">
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @include('stats.partials.unit-chart', [
                            'heading' => 'Proposals per research subject',
                            'unitChart' => $chart['researchsubject_preapproved'],
                            'badge' => 'Committed',
                            'note' => null,
                        ])
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @foreach (['sek', 'eur', 'usd'] as $currency)
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Committed budget',
                                'unitChart' => $chart['researchsubject_commited_'.$currency],
                                'badge' => strtoupper($currency),
                                'note' => null,
                            ])
                        @endforeach
                    </div>
                    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                        @include('stats.partials.unit-chart', [
                            'heading' => 'PhD years',
                            'unitChart' => $chart['researchsubject_phd'],
                            'badge' => 'Years',
                            'note' => null,
                        ])
                        <div class="lg:col-span-2">
                            @include('stats.partials.unit-chart', [
                                'heading' => 'Funding agency',
                                'unitChart' => $chart['agency'],
                                'badge' => 'Proposals',
                                'note' => null,
                            ])
                        </div>
                    </div>
                @include('stats.partials.investigator-chart')
            </div>
        </div>
    </section>
@endsection
