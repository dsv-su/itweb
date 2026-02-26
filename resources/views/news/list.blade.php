@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')

    @foreach ($collections as $collection)
        @if($loop->first)
            <!-- Title -->
            <div class="max-w-2xl mx-auto text-center mb-8 lg:mt-7 lg:mb-7">
                <h2 class="uppercase text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">{{__("$collection->collection") }}</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400">{{__("Latest entries")}}</p>
            </div>
        @endif

        <div class="px-4 mx-auto max-w-2xl">
            <!-- Card -->
            <a class="mb-3 group flex flex-col border border-blue-600 hover:border-transparent hover:shadow-lg
                    transition-all duration-300 rounded-xl p-5 dark:border-gray-700 dark:hover:border-transparent
                    dark:hover:shadow-black/[.4] dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{$collection->url}}">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                        <div class="grow">
                            <div class="flex justify-between items-center gap-x-2">
                                <div>
                                    <div class="inline-block">
                                        <div class="sm:mb-1 block text-start">
                                              <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                {!! $collection->author->name ?? '' !!}
                                              </span>
                                        </div>
                                    </div>
                                    <ul class="text-xs text-gray-500 dark:text-gray-300">
                                        <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1
                                        before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-400 dark:before:bg-gray-600">
                                            {!! $collection->date !!}
                                        </li>
                                        <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1
                                        before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-200 dark:before:bg-gray-600">
                                            {!! $collection->collection !!}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content -->
                <div class="space-y-5 md:space-y-8">
                    <div class="space-y-3">
                        <h2 class="text-xl font-normal md:text-xl dark:text-white inline-block"> {!! $collection->title !!}
                            <svg class="w-6 h-6 text-gray-800 dark:text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                            </svg>
                        </h2>

                    </div>
                </div>
            </a>
            <!-- End Card -->
    </div>
    @endforeach
@endsection
