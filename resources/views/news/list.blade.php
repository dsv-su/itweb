@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')

    {{-- Page header (render once) --}}
    @foreach ($collections as $collection)
        @if($loop->first)
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-6 mt-4 sm:mt-6 lg:mt-8 lg:mb-8">
                    <h2 class="uppercase text-2xl font-bold sm:text-3xl md:text-4xl md:leading-tight dark:text-white">
                        {{ __("$collection->collection") }}
                    </h2>
                    <p class="mt-1 text-sm sm:text-base text-gray-600 dark:text-gray-400">
                        {{ __("Latest entries") }}
                    </p>
                </div>
            </div>
        @endif

        @if($loop->first)
            <div class="mb-4 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <ul class="space-y-3">
        @endif

                    <li>
                        <a
                            href="{{ $collection->url }}"
                            class="group block rounded-xl border border-blue-600 p-4 sm:p-5
                                   transition-all duration-300
                                   hover:border-transparent hover:shadow-lg
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                   dark:border-gray-700 dark:hover:border-transparent dark:hover:shadow-black/[.4]
                                   dark:focus:ring-gray-600 dark:focus:ring-offset-gray-900"
                        >
                            <div class="flex flex-col gap-2 sm:gap-3">
                                {{-- Meta row --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200 break-words">
                                            {!! $collection->author->name ?? '' !!}
                                        </div>

                                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 dark:text-gray-300">
                                            <span class="inline-flex items-center">
                                                {!! $collection->date !!}
                                            </span>
                                            <span class="hidden sm:inline text-gray-300 dark:text-gray-600">•</span>
                                            <span class="inline-flex items-center text-gray-600 dark:text-gray-200">
                                                {!! $collection->collection !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Title row --}}
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-base sm:text-lg md:text-xl font-normal text-gray-900 dark:text-white break-words">
                                        {!! $collection->title !!}
                                    </h3>

                                    <svg
                                        class="w-5 h-5 sm:w-6 sm:h-6 shrink-0 text-gray-800 dark:text-white transition-transform duration-200 group-hover:translate-x-0.5"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </li>

        @if($loop->last)
                </ul>
            </div>
        @endif
    @endforeach
@endsection
