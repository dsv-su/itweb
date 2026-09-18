@extends('layouts.app')

@section('page-navigation')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 text-gray-950 dark:bg-gray-900 dark:text-white">
        <section class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-950">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">{{ __('Requests and approvals') }}</p>
                <h1 class="mt-2 text-3xl font-bold sm:text-4xl">{{ __('Notifications') }}</h1>
                <p class="mt-3 text-gray-600 dark:text-gray-400">{{ __('Review tasks, follow up on returned requests and track your submissions.') }}</p>
            </div>
        </section>
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <livewire:notifications />
        </div>
    </div>
@endsection
