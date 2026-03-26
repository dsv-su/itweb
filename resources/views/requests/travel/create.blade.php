@extends('layouts.app')

@section('content')
    @include('dsvheader')
    @include('navbar.navbar')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                {{ __('Duty Travel Request') }}
            </h2>

            <form method="post" action="{{ route('travel-submit') }}">
                @csrf

                @if($type === 'resume')
                    <input type="hidden" name="id" value="{{ $tr->id }}">
                @endif

                <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <!-- Name -->
                    <div class="w-full">
                        <label for="name" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('You may change this name') }}
                            <button
                                id="name-button"
                                data-modal-target="name-modal"
                                data-modal-toggle="name-modal"
                                class="inline"
                                type="button"
                            >
                                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            value="{{ old('name', $tr->name ?? ('Travelrequest for ' . auth()->user()->name)) }}"
                            placeholder="{{ __('Name') }}"
                            required
                        >

                        @error('name')
                            <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">
                                {{ __('This is a required input') }}
                            </p>
                        @enderror
                    </div>

                    <!-- Purpose -->
                    <div class="sm:col-span-2">
                        <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Purpose of the mission with the web address of the conference') }}
                            <span class="text-red-600"> *</span>

                            <button
                                id="purpose-button"
                                data-modal-target="purpose-modal"
                                data-modal-toggle="purpose-modal"
                                class="inline"
                                type="button"
                            >
                                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                        </label>

                        <textarea
                            id="purpose"
                            rows="4"
                            name="purpose"
                            class="@error('purpose') border-red-500 @enderror font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('Describe the purpose of your mission') }}"
                        >{{ old('purpose', $tr->purpose ?? '') }}</textarea>

                        @error('purpose')
                            <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">
                                {{ __('This is a required input') }}
                            </p>
                        @enderror
                    </div>

                    <!-- Paper accepted -->
                    <div class="w-full">
                        <label for="paper" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Paper accepted') }}
                            <button
                                id="paper-button"
                                data-modal-target="paper-modal"
                                data-modal-toggle="paper-modal"
                                class="inline"
                                type="button"
                            >
                                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                        </label>

                        <select
                            id="paper"
                            name="paper"
                            data-value="{{ old('paper', $tr->paper ?? 0) }}"
                            class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        >
                            @php($paperValue = (int) old('paper', $tr->paper ?? 0))
                            <option value="0" @selected($paperValue === 0)>{{ __('No') }}</option>
                            <option value="1" @selected($paperValue === 1)>{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <br>
                    <!-- Project -->
                    @include('requests.travel.partials.projecttab')

                    <!-- Country -->
                    <livewire:travel-type :resume="$type === 'resume' ? $tr->country : null" />

                    <!-- Project leader -->
                    <livewire:select2.projectleader-select2 />

                    <!-- Unit head -->
                    <div>
                        <label for="unit_head" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Unit Head') }}<span class="text-red-600"> *</span>
                            <button
                                id="unithead-button"
                                data-modal-target="unithead-modal"
                                data-modal-toggle="unithead-modal"
                                class="inline"
                                type="button"
                            >
                                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </button>
                        </label>

                        <select
                            id="unit_head"
                            name="unit_head"
                            class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        >
                            @foreach($unitheads as $unithead)
                                <option
                                    value="{{ $unithead->id }}"
                                    @selected($type === 'resume' && $unithead->id == $dashboard->head_id)
                                >
                                    {{ $unithead->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('unit_head')
                            <p class="mt-3 text-sm leading-6 text-red-600">{{ __('This is a required input') }}</p>
                        @enderror
                    </div>

                    <!-- Departure / return -->
                    <div date-rangepicker datepicker-format="yyyy-mm-dd" class="flex flex-col sm:flex-row sm:col-span-2 items-center dark:text-gray-200">
                        <div class="flex flex-col w-full sm:w-1/2">
                            <label for="startInput" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ __('From') }}
                            </label>

                            <div class="relative w-full sm:w-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-700 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>

                                <input
                                    name="departure"
                                    id="startInput"
                                    type="text"
                                    value="{{ old('departure', ($type === 'resume' && isset($tr?->departure)) ? \Carbon\Carbon::createFromTimestamp($tr->departure)->format('Y-m-d') : '') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="{{ __('Select date start') }}"
                                >

                                @error('departure')
                                    <p class="mt-3 text-sm leading-6 text-red-600">{{ __('This is a required input') }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col w-full sm:w-1/2 sm:mt-0 mt-4">
                            <label for="endInput" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ __('To') }}
                            </label>

                            <div class="relative w-full sm:w-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-700 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>

                                <input
                                    name="return"
                                    id="endInput"
                                    type="text"
                                    value="{{ old('return', ($type === 'resume' && isset($tr?->return)) ? \Carbon\Carbon::createFromTimestamp($tr->return)->format('Y-m-d') : '') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="{{ __('Select date end') }}"
                                >

                                @error('return')
                                    <p class="mt-3 text-sm leading-6 text-red-600">{{ __('This is a required input') }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Expenses -->
                    <div class="sm:col-span-2">
                        <label for="expenses" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Expenses') }}
                        </label>

                        <livewire:travel-request-expenses :tr="$type === 'resume' ? $tr : null" />
                    </div>
                </div>

                @include('requests.travel.partials.submit-buttons')
            </form>
        </div>
    </section>

    <!-- Modals -->
    @include('requests.travel.modals.travel_help')

    @push('scripts')
        <script>
            // Keeps Flowbite date-range picker from auto-setting the other input
            const startInput = document.getElementById('startInput');
            const endInput = document.getElementById('endInput');

            if (startInput) {
                startInput.addEventListener('changeDate', function (e) {
                    Livewire.dispatch('changeStartDate', { date: e.detail.datepicker.inputField.value });
                    e.detail.datepicker.hide();
                });
            }

            if (endInput) {
                endInput.addEventListener('changeDate', function (e) {
                    Livewire.dispatch('changeEndDate', { date: e.detail.datepicker.inputField.value });
                    e.detail.datepicker.hide();
                });
            }
        </script>
    @endpush
@endsection
