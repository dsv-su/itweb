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
                <div class="mt-6">
                    <x-chartjs-component :chart="$chart" />
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Each proposal counts once per unit, using its unit heads’ current units. Proposals involving several units count in each. Missing units appear as “Unknown unit”.
                </p>
            @else
                <p class="mt-6 text-gray-700 dark:text-gray-300">No matching proposals for {{ $fromYear }}.</p>
            @endif
        </div>
    </section>
@endsection
