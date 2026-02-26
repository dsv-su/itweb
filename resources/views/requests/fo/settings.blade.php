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
                            <div class="mt-4 bg-blue-50 border border-blue-500 text-sm text-gray-500 rounded-lg p-5 dark:bg-blue-600/[.15]">
                                <div class="flex">
                                    <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 16v-4"></path>
                                        <path d="M12 8h.01"></path>
                                    </svg>
                                    <div class="ms-3">
                                        <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please note!</h3>
                                        <p class="mt-2 text-gray-800 dark:text-slate-400">Please note that only one user can be selected to receive notifications.</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Select the officer that should receive the notifications</p>
                            <div class=" mt-5 border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
                                <div class="w-1/2 border border-blue-500 text-sm text-blue-600 rounded-lg p-5 dark:bg-blue-600/[.15]">
                                    {{\App\Models\SettingsFo::find(1)->name ?? 'Not set'}}
                                </div>
                                <form action="{{ route('fo') }}" method="POST">
                                    @csrf

                                    <div >
                                        <div class="mt-3">
                                            <label for="fo_select" class="block text-sm font-medium text-gray-700">Change:</label>
                                            <select id="fo_select" name="selected_fo" class="mt-1 block w-1/2 py-2 px-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                                @foreach($fos as $fo)
                                                    <option value="{{ $fo->id }}" @if($fo->active) selected @endif>{{ $fo->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="mt-3">
                                            <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                                                uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                                                disabled:opacity-25 transition ease-in-out duration-150">
                                                Update
                                            </button>
                                        </div>

                                    </div>


                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
