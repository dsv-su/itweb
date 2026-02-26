@extends('layouts.app')
@section('content')
@include('dsvheader')
@include('navbar.navbar')
<style>
    /* Media query for smaller screens */
    .gap-8 {
        gap: 8px; /* Adjust the gap between flex items */
    }

    /* Media query for screens smaller than 600px */
    @media (max-width: 600px) {
        .flex {
            display: flex;
            flex-wrap: wrap; /* Ensures items wrap to the next line on smaller screens */
        }
        .gap-8 {
            gap: 4px; /* Decrease the gap for smaller screens */
        }
    }
</style>
@if($formtype == 'fo_review')
    <form method="POST" action="{{route('fo_review', $dashboard)}}">
        @csrf
@elseif($formtype == 'returned')
    <form method="POST" action="{{route('travel-request-resume', $tr)}}">
        @csrf
@endif
<section class="bg-white dark:bg-gray-900">
    <div class="max-w-6xl px-4 py-8 mx-auto lg:py-16">
        <h2 class="inline mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ __("Duty Travel Request") }}</h2>
        <!-- Returned request -->
        @if($formtype == 'returned')
            <button type="submit" name="resume" value="resume"
                    class="inline mx-2 items-center justify-center px-5 hover:bg-yellow-400 font-semibold
                    hover:text-white py-2 px-4 border border-yellow-400 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-yellow-400 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Resume")}}</span>
            </button>
        @endif
        <div class="flex gap-8">
            <div class="w-3/4 grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <!--ID-->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("RequestID") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->id}}
                    </div>
                </div>

                <!--Name-->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Name") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->name}}
                    </div>
                </div>

                <!-- Purpose-->
                <div class="sm:col-span-2">
                    <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Purpose of the mission with the web address of the conference") }}
                    </label>
                    <textarea id="purpose" rows="4" name="purpose"
                    class="font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{__("Describe the purpose of your mission")}}" readonly>{{ $tr->purpose ?? '' }}</textarea>

                </div>
                <!--Paper accepted -->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Paper accepted") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @if($tr->paper) {{__("Yes")}} @else {{__("No")}} @endif
                    </div>
                </div>
                <br>

                <!-- Project -->
                @if($formtype == 'fo_review')
                    <livewire:select2.project-select2 :id="$tr->project">
                @else
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Project") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @if($tr->project)
                            {{$tr->project}} {{\App\Models\Project::where('project', $tr->project)->pluck('description')->first()}}
                        @else
                            NN
                        @endif

                    </div>
                </div>
                @endif
                <!--Country-->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Country") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->country}}
                    </div>
                </div>
                <!-- From to Dates -->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Departure date") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @if($tr->departure)
                            {{\Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString()}}
                        @else
                            NN
                        @endif
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Return date") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @if($tr->return)
                            {{\Carbon\Carbon::createFromTimestamp($tr->return)->toDateString()}}
                        @else
                            NN
                        @endif
                    </div>
                </div>

                <!--Expenses-->
                <label for="expenses" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Expenses") }}</label>
                <br>
                <div class="w-full">
                    <label for="flight" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Travel (Plane, train, etc)") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->flight ?? 0}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="hotel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Hotel") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->hotel ?? 0}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="conference" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Conference fee") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->conference ?? 0}} SEK
                    </div>
                </div>

                <div class="w-full">
                    <label for="other_costs" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Other costs") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->other_costs ?? 0}} SEK
                    </div>
                </div>

                <div class="w-full">
                    <label for="daily" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Daily subsistence allowances") }} </label>
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->country}}: {{ $tr->daily ?? 0}} x {{$tr->days ?? 0}} ({{__("days")}}) = {{$tr->daily * $tr->days}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Total") }} </label>
                    <div class="font-mono bg-blue-400 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->total ?? 0}} SEK
                    </div>
                </div>
            </div>

            @include('requests.travel.comments')

        </div>


    </div>


</section>
@if($formtype == 'review')
    <!-- Add Comments -->
    @include('review.bar')
@elseif($formtype == 'fo_review')
    @include('review.fobar')
@endif

@endsection
