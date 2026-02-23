<div class="md:order-1 relative border border-susecondary {{--}}border-gray-200{{--}} dark:border-gray-800 rounded-xl">
    <div class="relative overflow-hidden w-full h-full rounded-xl">
        <livewire:lecturerooms />
        <div class="px-6 {{--}}mt-6{{--}} flex flex-col {{--}}justify-center{{--}} {{--}}items-start{{--}} md:min-h-[480px] {{--}}text-center{{--}} rounded-xl dark:border-gray-700">
            <div id="middleHolder">
                <div class="flex flex-col border-y border-susecondary dark:border-gray-700">
                    <div class="pb-8">
                        @nocache('home.partials.teachernews')
                    </div>
                </div>

                <div class="mt-6 justify-center items-start text-center">

                    @include('home.partials.middle.center')

            </div>

                <div class="flex flex-col border-y dark:border-gray-700">
                    <h3 class="pt-8 text-left text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{__("PhD information")}}
                        @can('access cp')
                            <a href="/cp/collections/phdnews" aria-label="PhD news admin" class="float-right hover:border-blue-600">
                                <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.109 17H1v-2a4 4 0 0 1 4-4h.87M10 4.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm7.95 2.55a2 2 0 0 1 0 2.829l-6.364 6.364-3.536.707.707-3.536 6.364-6.364a2 2 0 0 1 2.829 0Z"/>
                                </svg>
                            </a>
                        @endif
                    </h3>

                    <div class="pb-8">
                        @nocache('home.partials.phdnews')
                    </div>
                </div>
            </div>
            <div id="lectureroomHolder" style="display: none;">
                <livewire:roomstatus />
            </div>


        </div>

        <div class="absolute top-0 inset-x-0 -z-[1] w-full h-full">
            <svg class="w-full h-full" width="411" height="476" viewBox="0 0 411 476" fill="none" xmlns="http://www.w3.org/2000/svg">
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
