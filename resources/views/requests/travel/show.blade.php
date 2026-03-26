@extends('layouts.app')

@section('content')
    @include('dsvheader')
    @include('navbar.navbar')

    @php
        $isFoReview = ($formtype ?? null) === 'fo_review';
        $isReturned = ($formtype ?? null) === 'returned';
        $isReview = ($formtype ?? null) === 'review';

        $projectDescription = null;
        if (! $isFoReview && filled($tr->project ?? null)) {
            $projectDescription = \App\Models\Project::query()
                ->where('project', $tr->project)
                ->value('description');
        }

        $departureDate = filled($tr->departure ?? null)
            ? \Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString()
            : null;

        $returnDate = filled($tr->return ?? null)
            ? \Carbon\Carbon::createFromTimestamp($tr->return)->toDateString()
            : null;

        $daily = (float) ($tr->daily ?? 0);
        $days = (int) ($tr->days ?? 0);
        $dailyTotal = $daily * $days;
    @endphp

    @if($isFoReview)
        <form method="POST" action="{{ route('fo_review', $dashboard) }}">
            @csrf
            @elseif($isReturned)
                <form method="POST" action="{{ route('travel-request-resume', $tr) }}">
                    @csrf
                    @endif

                    <section class="bg-white dark:bg-gray-900">
                        <div class="max-w-6xl mx-auto px-4 py-8 lg:py-16">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ __('Duty Travel Request') }}
                                </h2>

                                @if($isReturned)
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        <button
                                            type="submit"
                                            name="resume"
                                            value="resume"
                                            class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold
                                   border border-yellow-400 rounded
                                   text-yellow-600 hover:text-white hover:bg-yellow-400
                                   dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800"
                                        >
                                            {{ __('Resume') }}
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                @include('requests.travel.comments')
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ __('Request details') }}
                                    </h3>

                                    <div class="mt-4 grid gap-4 sm:grid-cols-2 sm:gap-6">

                                        <div class="sm:col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('RequestID') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->id }}
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Name') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->name }}
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Purpose of the mission with the web address of the conference') }}
                                            </label>
                                            <textarea
                                                id="purpose"
                                                rows="4"
                                                name="purpose"
                                                readonly
                                                class="font-mono block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                                        focus:ring-blue-500 focus:border-blue-500
                                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="{{ __('Describe the purpose of your mission') }}"
                                            >{{ $tr->purpose ?? '' }}</textarea>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Paper accepted') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ ($tr->paper ?? false) ? __('Yes') : __('No') }}
                                            </div>
                                        </div>

                                        <div class="hidden sm:block"></div>

                                        @if($isFoReview)
                                            <div class="sm:col-span-2">
                                                <livewire:select2.project-select2 :id="$tr->project" />
                                            </div>
                                        @else
                                            <div class="sm:col-span-2">
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ __('Project') }}
                                                </label>

                                                <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    @if(filled($tr->project ?? null))
                                                        {{ $tr->project }} {{ $projectDescription }}
                                                    @else
                                                        {{ __('NN') }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Country') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->country }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Departure date') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $departureDate ?? __('NN') }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Return date') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $returnDate ?? __('NN') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ __('Expenses') }}
                                    </h3>

                                    <div class="mt-4 grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Travel (Plane, train, etc)') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->flight ?? 0 }} SEK
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Hotel') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->hotel ?? 0 }} SEK
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Conference fee') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->conference ?? 0 }} SEK
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Other costs') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->other_costs ?? 0 }} SEK
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Daily subsistence allowances') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->country }}: {{ $daily }} x {{ $days }} ({{ __('days') }}) = {{ $dailyTotal }} SEK
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ __('Total') }}
                                            </label>
                                            <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-blue-400 text-gray-900
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                {{ $tr->total ?? 0 }} SEK
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    @if($isFoReview || $isReturned)
                </form>
    @endif

    @if($isReview)
        @include('review.bar')
    @elseif($isFoReview)
        @include('review.fobar')
    @endif
@endsection
