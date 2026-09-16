@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    <!-- PP header -->
    @include('pp.partials.header')

    @include('pp.partials.breadcrumb')

    @include('pp.partials.flashmessage')

    @include('stats.partials.tabs')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-6xl px-4 py-8 mx-auto lg:py-16">
            @include('stats.partials.controls', ['title' => 'Committed Proposals'])

            <!-- First row -->
            <div class="lg:flex lg:gap-8 mt-6">
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_preapproved']" />
                </div>

            </div>
            <!-- Second row -->
            <div class="lg:flex lg:gap-6 mt-6">
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_commited_sek']" />
                </div>
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_commited_eur']" />
                </div>
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_commited_usd']" />
                </div>
            </div>

            <!-- Third row -->
            <div class="lg:flex lg:gap-8 mt-6">
                <div class="w-full lg:w-1/2">
                    <h5 class="text-xl dark:text-white">Funding Agency</h5>
                    <x-chartjs-component :chart="$chart['agency']" />
                </div>
                <div class="w-full lg:w-1/2">
                    <h5 class="text-xl dark:text-white">PhD years</h5>
                    <x-chartjs-component :chart="$chart['researchsubject_phd']" />
                </div>
            </div>
            {{--}}
            <div class="flex justify-between items-center">
                <h3 class="text-2xl dark:text-white">Granted Proposals</h3>
            </div>
            {{--}}
        </div>
    </section>
@endsection
