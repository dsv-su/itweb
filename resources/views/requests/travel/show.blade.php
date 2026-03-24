@extends('layouts.app')
@section('content')
    @include('dsvheader')
    @include('navbar.navbar')

    @if($formtype == 'fo_review')
        <form method="POST" action="{{ route('fo_review', $dashboard) }}">
            @csrf
            @elseif($formtype == 'returned')
                <form method="POST" action="{{ route('travel-request-resume', $tr) }}">
                    @csrf
                    @endif

                    <section class="bg-white dark:bg-gray-900">
                        <div class="max-w-6xl mx-auto px-4 py-8 lg:py-16">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ __("Duty Travel Request") }}
                                </h2>

                                @if($formtype == 'returned')
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
                                            {{ __("Resume") }}
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                @include('requests.travel.comments')
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <!--ID-->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("RequestID") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->id }}
                                    </div>
                                </div>

                                <!--Name-->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Name") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->name }}
                                    </div>
                                </div>

                                <!-- Purpose-->
                                <div class="sm:col-span-2">
                                    <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Purpose of the mission with the web address of the conference") }}
                                    </label>
                                    <textarea
                                        id="purpose"
                                        rows="4"
                                        name="purpose"
                                        readonly
                                        class="font-mono block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                            focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                                        placeholder="{{ __("Describe the purpose of your mission") }}"
                                    >{{ $tr->purpose ?? '' }}</textarea>
                                </div>

                                <!--Paper accepted -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Paper accepted") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @if($tr->paper) {{ __("Yes") }} @else {{ __("No") }} @endif
                                    </div>
                                </div>

                                <div class="hidden sm:block"></div>

                                <!-- Project -->
                                @if($formtype == 'fo_review')
                                    <div class="sm:col-span-2">
                                        <livewire:select2.project-select2 :id="$tr->project" />
                                    </div>
                                @else
                                    <div class="sm:col-span-2">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ __("Project") }}
                                        </label>
                                        <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @if($tr->project)
                                                {{ $tr->project }} {{ \App\Models\Project::where('project', $tr->project)->pluck('description')->first() }}
                                            @else
                                                NN
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!--Country-->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Country") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->country }}
                                    </div>
                                </div>

                                <!-- From to Dates -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Departure date") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @if($tr->departure)
                                            {{ \Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString() }}
                                        @else
                                            NN
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Return date") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @if($tr->return)
                                            {{ \Carbon\Carbon::createFromTimestamp($tr->return)->toDateString() }}
                                        @else
                                            NN
                                        @endif
                                    </div>
                                </div>

                                <!--Expenses-->
                                <div class="sm:col-span-2 mt-2">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Expenses") }}
                                    </h3>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Travel (Plane, train, etc)") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->flight ?? 0 }} SEK
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Hotel") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->hotel ?? 0 }} SEK
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Conference fee") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->conference ?? 0 }} SEK
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Other costs") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->other_costs ?? 0 }} SEK
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Daily subsistence allowances") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 wrap-break-word
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->country }}: {{ $tr->daily ?? 0 }} x {{ $tr->days ?? 0 }} ({{ __("days") }}) = {{ ($tr->daily ?? 0) * ($tr->days ?? 0) }} SEK
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __("Total") }}
                                    </label>
                                    <div class="font-mono w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-blue-400 text-gray-900
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        {{ $tr->total ?? 0 }} SEK
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

        @if($formtype == 'review')
            @include('review.bar')
        @elseif($formtype == 'fo_review')
            @include('review.fobar')
        @endif

        @endsection
