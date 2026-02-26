@extends('layouts.app')
@include('dsvheader')
@include('navbar.navbar')
<div class="col-span-12 md:col-span-6 md:order-2 lg:col-span-4 grid gap-2.5 xl:gap-4">
    <!-- Card middle-->
    <div class="md:order-1 relative before:absolute before:inset-0 before:z-10 before:border before:border-gray-200 before:rounded-xl before:transition {{--}}before:hover:border-2 before:hover:border-blue-600 before:hover:shadow-lg{{--}} dark:before:border-gray-800 {{--}}dark:before:hover:border-blue-500{{--}}">
        <div class="relative overflow-hidden w-full h-full rounded-xl">
            <div class="p-6 flex flex-col justify-center items-center md:min-h-[480px] text-center rounded-xl dark:border-gray-700">
                <p class="bg-clip-text bg-gradient-to-l from-purple-400 to-blue-600 text-transparent text-xs font-semibold uppercase">
                    Scheduled for launch early
                </p>
                <span class="bg-clip-text bg-gradient-to-l from-purple-400 to-blue-600 text-transparent text-7xl font-bold">
                          2024
                        </span>
                <h3 class="mt-6 text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">
                    DSV Workflows
                </h3>
                <p class="mt-2 text-gray-500">
                    Is under construction. Scheduled for launch early 2024
                </p>
                <p class="mt-2 text-gray-500">
                   {{$wf}}
                </p>
            </div>

            <div class="absolute top-0 inset-x-0 -z-[1] w-full h-full">
                <svg class="w-full h-full" width="411" height="476" viewBox="0 0 411 476" fill="none" xmlns="http://www.w3.org/2000/svg">

                    <circle opacity="0.4" cx="401.833" cy="560.5" r="317" stroke="#DDD6FE" stroke-dasharray="3 0"></circle>
                    <circle opacity="0.6" cx="401.833" cy="560.5" r="245" stroke="#DDD6FE" stroke-dasharray="3 0"></circle>
                    <circle opacity="0.8" cx="401.833" cy="560.5" r="168" stroke="#DDD6FE" stroke-dasharray="3 0"></circle>
                    <circle cx="401.833" cy="560.5" r="99" stroke="#DDD6FE" stroke-dasharray="3 0"></circle>

                    <g filter="url(#filter0_f_6966_190348)">
                        <rect x="281.333" y="498" width="240.294" height="124.936" fill="#DAEAFF" fill-opacity="0.9"></rect>
                    </g>
                    <g filter="url(#filter1_f_6966_190348)">
                        <rect x="233.333" y="-177" width="240.294" height="124.936" fill="#E2CCFF" fill-opacity="0.35"></rect>
                    </g>
                    <g filter="url(#filter2_f_6966_190348)">
                        <rect x="233.333" y="-177" width="240.294" height="124.936" fill="#DAEAFF" fill-opacity="0.5"></rect>
                    </g>
                    <g filter="url(#filter3_f_6966_190348)">
                        <rect x="81.5195" y="194.5" width="240.294" height="124.936" fill="#E2CCFF" fill-opacity="0.35"></rect>
                    </g>
                    <defs>
                        <filter id="filter0_f_6966_190348" x="81.3333" y="298" width="640.294" height="524.936" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                            <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_6966_190348"></feGaussianBlur>
                        </filter>
                        <filter id="filter1_f_6966_190348" x="33.3333" y="-377" width="640.294" height="524.936" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                            <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_6966_190348"></feGaussianBlur>
                        </filter>
                        <filter id="filter2_f_6966_190348" x="33.3333" y="-377" width="640.294" height="524.936" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                            <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_6966_190348"></feGaussianBlur>
                        </filter>
                        <filter id="filter3_f_6966_190348" x="-118.48" y="-5.5" width="640.294" height="524.936" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                            <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_6966_190348"></feGaussianBlur>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
    </div>
</div>
<!-- End Col -->
