@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    <!-- PP header -->
    @include('pp.partials.header')

    <!-- Content -->
    <div class="w-full">
        <div class="p-2 sm:p-4 space-y-2 sm:space-y-4">
           <!-- -->
            <div class="bg-white">

                <div class="relative isolate px-4 sm:px-6 pt-8 sm:pt-12 lg:px-8 overflow-hidden">
                    <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                        <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
                    </div>

                    <div class="mx-auto max-w-2xl py-14 sm:py-24 lg:py-56">
                        <div class="mb-6 flex justify-center sm:mb-8">
                            <div class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                                Go to the Current
                                <a href="https://projectproposals.dsv.su.se" class="font-semibold text-indigo-600">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Project Proposals <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <h1 class="text-3xl sm:text-5xl lg:text-7xl font-semibold tracking-tight text-balance text-gray-900">
                                Project Proposals
                            </h1>
                            <p class="mt-4 sm:mt-8 text-base sm:text-lg sm:text-xl/8 font-medium text-pretty text-gray-500">
                                The new portal launches on April 1
                            </p>
                        </div>
                    </div>

                    <div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                        <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75"></div>
                    </div>
                </div>
            </div>

            <!-- -->
        </div>
    </div>
    <!-- End Content -->
@endsection
