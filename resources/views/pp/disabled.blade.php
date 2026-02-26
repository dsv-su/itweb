@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    <!-- PP header -->
    @include('pp.partials.header')

    @include('pp.partials.breadcrumb')

    @include('pp.partials.flashmessage')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-6xl px-4 py-8 mx-auto lg:py-16">
            <!-- Flex container to align button to the right -->
            <div class="flex justify-between items-center">
                <h3 class="text-2xl dark:text-white">Project proposals is currently unavailable at this time.</h3>
            </div>

        </div>
    </section>
@endsection
