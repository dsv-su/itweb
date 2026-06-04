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

        $valueClass = 'font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 break-words dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200';
        $textareaClass = 'font-mono block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 break-words focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400';
        $labelClass = 'block mb-2 text-sm font-medium text-gray-900 dark:text-white';
    @endphp

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ __('Duty Travel Request') }}
                </h2>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a
                        href="{{ url()->previous() }}"
                        class="inline-flex w-full items-center justify-center rounded-lg border border-blue-700 bg-white px-5 py-2.5 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-800 hover:text-white dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800 sm:w-auto"
                    >
                        {{ __('Back') }}
                    </a>

                    @if($isReturned)
                        <form method="POST" action="{{ route('travel-request-resume', $tr) }}" class="sm:flex-none">
                            @csrf
                            <button
                                type="submit"
                                name="resume"
                                value="resume"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-yellow-400 bg-white px-5 py-2.5 text-sm font-semibold text-yellow-600 shadow-sm hover:bg-yellow-400 hover:text-white dark:border-gray-600 dark:bg-neutral-900 dark:text-gray-200 dark:hover:bg-gray-800 sm:w-auto"
                            >
                                {{ __('Resume') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                @include('requests.travel.comments')
            </div>

            @if($isFoReview)
                <form method="POST" action="{{ route('fo_review', $dashboard) }}">
                    @csrf
            @endif

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <section class="rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                        <div class="rounded-t-xl border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('Request details') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Name and purpose for this duty travel request') }}
                            </p>
                        </div>

                        <div class="grid gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('RequestID') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->id }}
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Paper accepted') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ ($tr->paper ?? false) ? __('Yes') : __('No') }}
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="{{ $labelClass }}">
                                    {{ __('Name') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->name }}
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="purpose" class="{{ $labelClass }}">
                                    {{ __('Purpose of the mission with the web address of the conference') }}
                                </label>
                                <textarea
                                    id="purpose"
                                    rows="4"
                                    name="purpose"
                                    readonly
                                    class="{{ $textareaClass }}"
                                    placeholder="{{ __('Describe the purpose of your mission') }}"
                                >{{ $tr->purpose ?? '' }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="relative z-30 sm:col-span-2">
                    <section class="rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                        <div class="rounded-t-xl border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('Project') }} & {{ __('Country') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Project and destination country for this duty travel request') }}
                            </p>
                        </div>

                        <div class="grid min-w-0 gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                            @if($isFoReview)
                                <div class="sm:col-span-2">
                                    <livewire:select2.project-select2 :id="$tr->project" />
                                </div>
                            @else
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">
                                        {{ __('Project') }}
                                    </label>

                                    <div class="{{ $valueClass }}">
                                        @if(filled($tr->project ?? null))
                                            {{ $tr->project }} {{ $projectDescription }}
                                        @else
                                            {{ __('NN') }}
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="sm:col-span-2">
                                <label class="{{ $labelClass }}">
                                    {{ __('Country') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->country }}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="sm:col-span-2">
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                        <div class="border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('Travel dates') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Departure and return dates for this trip') }}
                            </p>
                        </div>

                        <div class="grid gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Departure date') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $departureDate ?? __('NN') }}
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Return date') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $returnDate ?? __('NN') }}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="sm:col-span-2">
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                        <div class="border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ __('Expenses') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Estimated costs for the duty travel request') }}
                                    </p>
                                </div>

                                <span class="hidden rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-200 dark:ring-blue-800 sm:inline-flex">
                                    {{ __('SEK') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Travel (Plane, train, etc)') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->flight ?? 0 }} SEK
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Hotel') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->hotel ?? 0 }} SEK
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Conference fee') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->conference ?? 0 }} SEK
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    {{ __('Other costs') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->other_costs ?? 0 }} SEK
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="{{ $labelClass }}">
                                    {{ __('Daily subsistence allowances') }}
                                </label>
                                <div class="{{ $valueClass }}">
                                    {{ $tr->country }}: {{ $daily }} x {{ $days }} ({{ __('days') }}) = {{ $dailyTotal }} SEK
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="{{ $labelClass }}">
                                    {{ __('Total') }}
                                </label>
                                <div class="font-mono w-full rounded-lg border border-blue-200 bg-blue-50 p-2.5 text-sm font-semibold text-blue-900 break-words dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-100">
                                    {{ $tr->total ?? 0 }} SEK
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            @if($isFoReview)
                </form>
            @endif
        </div>
    </section>

    @if($isReview)
        @include('review.bar')
    @elseif($isFoReview)
        @include('review.fobar')
    @endif
@endsection
