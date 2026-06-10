@extends('layouts.app')

@php
    $isResume = $type === 'resume';
    $travelRequest = $isResume ? $tr : null;
    $dashboardRequest = $isResume ? $dashboard : null;

    $defaultName = 'Travelrequest for ' . auth()->user()->name;
    $paperValue = (int) old('paper', $travelRequest?->paper ?? 0);

    $formatTravelDate = fn ($timestamp) => $timestamp
        ? \Carbon\Carbon::createFromTimestamp($timestamp)->format('Y-m-d')
        : '';

    $inputClass = 'font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500';
    $selectClass = 'font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500';
    $textareaClass = 'font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';
@endphp

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

                @if($isResume)
                    <input type="hidden" name="id" value="{{ $travelRequest->id }}">
                @endif

                <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <div class="sm:col-span-2">
                        <section class="rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                            <div class="rounded-t-xl border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ __('Request details') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Name this request and describe the purpose of the mission') }}
                                </p>
                            </div>

                            <div class="grid gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                                <div class="w-full">
                                    @include('requests.travel.partials.form.field-label', [
                                        'for' => 'name',
                                        'label' => __('You may change this name'),
                                        'modal' => 'name',
                                        'class' => 'font-sans',
                                    ])

                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="{{ $inputClass }}"
                                        value="{{ old('name', $travelRequest?->name ?? $defaultName) }}"
                                        placeholder="{{ __('Name') }}"
                                        required
                                    >

                                    @include('requests.travel.partials.form.field-error', [
                                        'field' => 'name',
                                        'scrollToForm' => true,
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('requests.travel.partials.form.field-label', [
                                        'for' => 'paper',
                                        'label' => __('Paper accepted'),
                                        'modal' => 'paper',
                                    ])

                                    <select
                                        id="paper"
                                        name="paper"
                                        data-value="{{ $paperValue }}"
                                        class="{{ $selectClass }}"
                                    >
                                        <option value="0" @selected($paperValue === 0)>{{ __('No') }}</option>
                                        <option value="1" @selected($paperValue === 1)>{{ __('Yes') }}</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    @include('requests.travel.partials.form.field-label', [
                                        'for' => 'purpose',
                                        'label' => __('Purpose of the mission with the web address of the conference'),
                                        'required' => true,
                                        'modal' => 'purpose',
                                    ])

                                    <textarea
                                        id="purpose"
                                        rows="4"
                                        name="purpose"
                                        class="@error('purpose') border-red-500 @enderror {{ $textareaClass }}"
                                        placeholder="{{ __('Describe the purpose of your mission') }}"
                                    >{{ old('purpose', $travelRequest?->purpose ?? '') }}</textarea>

                                    @include('requests.travel.partials.form.field-error', [
                                        'field' => 'purpose',
                                        'scrollToForm' => true,
                                    ])
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
                                    {{ __('Select the project and destination country for this duty travel request') }}
                                </p>
                            </div>

                            <div class="grid min-w-0 gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-6 sm:px-5">
                                @include('requests.travel.partials.projecttab')

                                <livewire:travel-type :resume="$isResume ? $travelRequest->country : null" />

                                <div class="sm:col-span-2">
                                    @include('requests.travel.partials.form.field-label', [
                                        'for' => 'comments',
                                        'label' => __('Comments'),
                                    ])

                                    <textarea
                                        id="comments"
                                        rows="3"
                                        name="comments"
                                        class="@error('comments') border-red-500 @enderror {{ $textareaClass }}"
                                        placeholder="{{ __('Add any comments about the project or destination country') }}"
                                    >{{ old('comments', $travelRequest?->comments ?? '') }}</textarea>

                                    @include('requests.travel.partials.form.field-error', [
                                        'field' => 'comments',
                                        'scrollToForm' => true,
                                    ])
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="sm:col-span-2">
                        <section class="rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-800/70">
                            <div class="rounded-t-xl border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ __('Projectleader') }} & {{ __('Unit Head') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Select who should review and approve this duty travel request') }}
                                </p>
                            </div>

                            <div class="grid gap-4 px-5 py-5 sm:grid-cols-2 sm:gap-6">
                                <livewire:select2.projectleader-select2 :projectleader-id="old('project_leader', $isResume ? $dashboardRequest?->manager_id : null)" />

                                <div>
                                    @include('requests.travel.partials.form.field-label', [
                                        'for' => 'unit_head',
                                        'label' => __('Unit Head'),
                                        'required' => true,
                                        'modal' => 'unithead',
                                    ])

                                    <select
                                        id="unit_head"
                                        name="unit_head"
                                        class="{{ $selectClass }}"
                                    >
                                        @foreach($unitheads as $unithead)
                                            <option
                                                value="{{ $unithead->id }}"
                                                @selected((string) old('unit_head', $isResume ? $dashboardRequest?->head_id : '') === (string) $unithead->id)
                                            >
                                                {{ $unithead->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @include('requests.travel.partials.form.field-error', ['field' => 'unit_head'])
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
                                    {{ __('Select the departure and return dates for this trip') }}
                                </p>
                            </div>

                            <div
                                date-rangepicker
                                datepicker-format="yyyy-mm-dd"
                                class="flex flex-col px-5 py-5 sm:flex-row sm:gap-4 dark:text-gray-200"
                            >
                                @include('requests.travel.partials.form.date-input', [
                                    'id' => 'startInput',
                                    'name' => 'departure',
                                    'label' => __('From'),
                                    'value' => old('departure', $formatTravelDate($travelRequest?->departure)),
                                    'placeholder' => __('Select date start'),
                                ])

                                @include('requests.travel.partials.form.date-input', [
                                    'id' => 'endInput',
                                    'name' => 'return',
                                    'label' => __('To'),
                                    'value' => old('return', $formatTravelDate($travelRequest?->return)),
                                    'placeholder' => __('Select date end'),
                                    'wrapperClass' => 'sm:mt-0 mt-4',
                                ])
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

                            <div class="px-5 py-5">
                                <livewire:travel-request-expenses :tr="$isResume ? $travelRequest : null" />
                            </div>
                        </section>
                    </div>
                </div>

                @include('requests.travel.partials.submit-buttons')
            </form>
        </div>
    </section>

    @include('requests.travel.modals.travel_help')

    @push('scripts')
        <script>
            const travelDateEvents = {
                startInput: 'changeStartDate',
                endInput: 'changeEndDate',
            };

            Object.entries(travelDateEvents).forEach(([inputId, eventName]) => {
                const input = document.getElementById(inputId);

                if (!input) {
                    return;
                }

                const dispatchTravelDate = () => {
                    Livewire.dispatch(eventName, {
                        date: input.value,
                    });
                };

                input.addEventListener('changeDate', (event) => {
                    dispatchTravelDate();

                    event.detail.datepicker.hide();
                });

                input.addEventListener('change', dispatchTravelDate);
            });
        </script>
    @endpush
@endsection
