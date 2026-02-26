@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    <!-- PP header -->
    @include('pp.partials.header')
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full">
                        <div class="w-full pt-10 px-4 sm:px-6 md:px-8 lg:ps-[22rem]">
                            <div class="lg:max-w-5xl mx-auto xl:max-w-6xl xl:ms-0 xl:me-48 xl:pe-12">
                                <header class="border-b pb-10 mb-10 dark:border-gray-700">
                                    <p class="mb-2 text-sm font-semibold text-blue-600">Vice Head</p>
                                    <h1 class="block text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">Project Proposals Settings</h1>
                                    <p class="mt-2 text-lg text-gray-800 dark:text-gray-400">Settings for vice head.</p>
                                </header>
                                <!-- Form enabled/disable -->
                                <livewire:pp.form-enable :fos="$fos" :vicehead="$vicehead" :oh="$oh"/>
                                <!-- Research area -->
                                <p class="mt-1 text-gray-600 dark:text-gray-400">Research subjects</p>
                                <div class="mt-4 bg-blue-50 border border-blue-500 text-sm text-gray-500 rounded-lg p-5 dark:bg-blue-600/[.15]">
                                    <div class="flex">
                                        <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 16v-4"></path>
                                            <path d="M12 8h.01"></path>
                                        </svg>
                                        <div class="ms-3">
                                            <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please note!</h3>
                                            <p class="mt-2 text-gray-800 dark:text-slate-400">
                                                Removing a research area in production may impact existing project proposals in the system.
                                                However, you can safely edit a research area's name or add a new one without any issues.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <livewire:pp.research-area />
                                <br>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">Funding organizations</p>
                                <livewire:pp.funding-org-edit />
                                <br>
                                @include('pp.partials.flashmessage')
                                <p class="mt-1 text-gray-600 font-semibold dark:text-gray-400">Overhead settings</p>
                                @include('requests.vice.partials.oh')
                                <br>
                                <p class="mt-1 text-gray-600 font-semibold dark:text-gray-400">DSV Registrator</p>
                                @include('requests.vice.partials.registrator')
                                <!-- Budget template upload -->
                                <br>
                                <p class="mt-1 text-gray-600 font-semibold dark:text-gray-400">Budget template</p>
                                <livewire:pp.budget-template-uploader :template="$template" />
                                <!-- FO -->
                                <br>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">Other settings</p>
                                <p class="mt-1 text-gray-600 font-semibold dark:text-gray-400">Financial Officer EU projects</p>
                                @include('requests.vice.partials.fo_eu')
                                <p class="mt-1 text-gray-600 font-semibold dark:text-gray-400">Financial Officer Other projects</p>
                                @include('requests.vice.partials.fo')
                                <!-- end FO -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
