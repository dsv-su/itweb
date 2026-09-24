@extends('layouts.app', ['title' => __('travel.disabled_title')])

@section('content')
    <section class="min-h-[50vh] bg-gray-50 px-4 py-16 text-gray-950 dark:bg-gray-900 dark:text-white">
        <div class="mx-auto max-w-3xl">
            <h1 class="text-3xl font-bold">{{ __('travel.disabled_title') }}</h1>
            <p class="mt-4 text-gray-600 dark:text-gray-300">{{ __('travel.disabled_message') }}</p>
            <a href="{{ app()->getLocale() === 'sv' ? url('/swe') : url('/?lang=en') }}" class="mt-6 inline-block rounded text-blue-700 underline focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 dark:text-blue-300">
                {{ __('travel.back_home') }}
            </a>
        </div>
    </section>
@endsection
