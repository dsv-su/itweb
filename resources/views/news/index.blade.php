@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')

    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">{{__("News")}}</h2>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Latest {{$collection}}</p>
        </div>
        <!-- End Title -->

    @switch($collection)
        @case('news')
            @foreach(\Statamic\Statamic::tag('collection:news') as $page)
                <!-- Card -->
                <a class="mb-3 group flex flex-col border border-blue-600 hover:border-transparent hover:shadow-lg
                        transition-all duration-300 rounded-xl p-5 dark:border-gray-700 dark:hover:border-transparent
                        dark:hover:shadow-black/[.4] dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                            <div class="grow">
                                <div class="flex justify-between items-center gap-x-2">
                                    <div>
                                        <div class="inline-block">
                                            <div class="sm:mb-1 block text-start">
                                                  <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                    {!! $page->author->name ?? '' !!}
                                                  </span>
                                            </div>
                                        </div>
                                        <ul class="text-xs text-gray-500">
                                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-300 dark:before:bg-gray-600">
                                                {!! $page->date !!}
                                            </li>
                                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-200 dark:before:bg-gray-600">
                                                {!! $page->collection !!}
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
                            <h2 class="text-xl font-bold md:text-xl dark:text-white"> {!! $page->title !!}</h2>
                            <p class="text-lg text-gray-800 dark:text-gray-200">{!! \Illuminate\Support\Str::limit($page->content, 16) !!}</p>
                        </div>
                    </div>
                    </a>
                    <!-- End Card -->
            @endforeach
        @break
        @case('itnews')
        @foreach(\Statamic\Statamic::tag('collection:itnews') as $page)
            <!-- Card -->
                <a class="mb-3 group flex flex-col border border-blue-600 hover:border-transparent hover:shadow-lg
                        transition-all duration-300 rounded-xl p-5 dark:border-gray-700 dark:hover:border-transparent
                        dark:hover:shadow-black/[.4] dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                            <div class="grow">
                                <div class="flex justify-between items-center gap-x-2">
                                    <div>
                                        <div class="inline-block">
                                            <div class="sm:mb-1 block text-start">
                                                  <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                    {!! $page->author->name ?? '' !!}
                                                  </span>
                                            </div>
                                        </div>
                                        <ul class="text-xs text-gray-500">
                                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-300 dark:before:bg-gray-600">
                                                {!! $page->date !!}
                                            </li>
                                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-gray-300 before:rounded-full dark:text-gray-300 dark:before:bg-gray-600">
                                                {!! $page->collection !!}
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
                            <h2 class="text-xl font-bold md:text-xl dark:text-white"> {!! $page->title !!}</h2>
                            <p class="text-lg text-gray-800 dark:text-gray-200">{!! \Illuminate\Support\Str::limit($page->content, 16) !!}</p>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            @endforeach
            @break
    @endswitch
    </div>
@endsection
