@extends('layouts.app')
@include('dsvheader')
@include('navbar.navbar')
<section class="bg-white dark:bg-gray-900">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">

        <div class="relative w-fit-content">
            <div class="absolute top-0 right-0 w-32">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">DSVTR-231025-1</span>
            </div>
        </div>

        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ __("Duty Travel Request") }}</h2>

            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <!--Name-->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Name") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->name}}
                    </div>
                </div>

                <!-- Purpose-->
                <div class="sm:col-span-2">
                    <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Purpose of the mission with the web address of the conference") }}
                    </label>
                    <textarea id="purpose" rows="4" name="purpose"
                              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                              placeholder="Purpose of the mission" readonly>
                    {{ $tr->purpose}}
                    </textarea>

                </div>
                <!-- Project -->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Project") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->project}}
                    </div>
                </div>

                <!--Country-->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Country") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->country}}
                    </div>
                </div>

                <!--Paper accepted -->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Paper accepted") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @if($tr->paper) {{__("Yes")}} @else {{__("No")}} @endif
                    </div>
                </div>
                <!--Contribution -->
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Contribution from") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->contribution ?? 'No contribution'}}
                    </div>
                </div>
                <!--Other reason-->
                <div class="sm:col-span-2">
                    <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Other reason. Justify") }}</label>
                    <textarea id="reason" rows="2"  name="reason"
                              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                              placeholder="Write a product description here...">
                    {{ $tr->other }}
                    </textarea>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Departure date") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{\Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString()}}
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Return date") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{\Carbon\Carbon::createFromTimestamp($tr->return)->toDateString()}}
                    </div>
                </div>

                <!--Expenses-->
                <label for="expenses" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Expenses") }}</label>
                <br>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Flight") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->flight ?? 0}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Hotel") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->hotel ?? 0}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Daily subsistence allowances") }} </label>
                    <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->country}}: {{ $tr->daily ?? 0}} x {{$tr->days ?? 0}} ({{__("days")}}) = {{$tr->daily * $tr->days}} SEK
                    </div>
                </div>
                <div class="w-full">
                    <label for="project" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Total") }} </label>
                    <div class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block
                    w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$tr->total ?? 0}} SEK
                    </div>
                </div>
            </div>
        @if($formtype == 'review')
            <!-- Add Comments -->
            @include('review.bar')
        @endif

    </div>
</section>
