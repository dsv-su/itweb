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
            @include('stats.partials.controls', ['title' => 'Granted Proposals'])

            <!-- First row -->
            <div class="lg:flex lg:gap-8 mt-6">
                <div class="w-full lg:w-1/2">
                    <h5 class="text-xl dark:text-white">Granted Amount(SEK)</h5>
                    <x-chartjs-component :chart="$chart['researchsubject_granted_sek']" />
                </div>

                <div class="w-full lg:w-1/2">
                    <h5 class="text-xl dark:text-white">Co finacing promised(SEK)</h5>
                    <x-chartjs-component :chart="$chart['researchsubject_promised_sek']" />
                </div>

            </div>

            <!-- Second row -->
            <div class="lg:flex lg:gap-8 mt-6">
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_granted_eur']" />
                </div>

                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_promised_eur']" />
                </div>
            </div>
            <!-- Third row -->
            <div class="lg:flex lg:gap-8 mt-6">
                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_granted_usd']" />
                </div>

                <div class="w-full lg:w-1/2">
                    <x-chartjs-component :chart="$chart['researchsubject_promised_usd']" />
                </div>
            </div>

        </div>
    </section>
@endsection
