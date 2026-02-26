@extends('layouts.app')
@section('content')
@include('dsvheader')
@include('navbar.navbar')
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full">
                    <livewire:request-search />
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
