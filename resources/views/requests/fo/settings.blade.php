@extends('layouts.app')
@section('content')
@include('dsvheader')
@include('navbar.navbar')
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full">
                    <div class="w-full pt-10 px-4 sm:px-6 md:px-8 lg:ps-[22rem]">
                        <div class="lg:max-w-3xl mx-auto xl:max-w-none xl:ms-0 xl:me-64 xl:pe-16">
                            <header class="border-b pb-10 mb-10 dark:border-gray-700">
                                <p class="mb-2 text-sm font-semibold text-blue-600">Financial Officer</p>
                                <h1 class="block text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">Settings</h1>
                                <p class="mt-2 text-lg text-gray-800 dark:text-gray-400">Settings for finance.</p>
                            </header>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Select language for PDF-prints</p>
                            <div class="mt-5 space-y-4">
                                <div class="mt-3">
                                    <div class="border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
                                        <ul class="max-w-sm flex flex-col">

                                            <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-list-group-item-radio-1" name="hs-list-group-item-radio" type="radio" class="border-gray-200 rounded-full disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" checked="">
                                                    </div>
                                                    <label for="hs-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-gray-500">Svenska</label>
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-list-group-item-radio-1" name="hs-list-group-item-radio" type="radio" class="border-gray-200 rounded-full disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                                    </div>
                                                    <label for="hs-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-gray-500">English</label>
                                                </div>
                                            </li>

                                        </ul>
                                        <div class="mt-3">
                                            <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                                                uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                                                disabled:opacity-25 transition ease-in-out duration-150" disabled>
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <br>
                            @if(session('status'))
                                <p role="status" class="text-sm text-green-700">{{ session('status') }}</p>
                            @endif
                            @include('requests.vice.partials.fo')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
