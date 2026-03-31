<div class="md:order-2 relative overflow-hidden rounded-xl">
    <div class="relative overflow-hidden p-6 flex flex-col justify-start items-start {{--}}md:min-h-[480px]{{--}}md:h-fit text-center rounded-xl
                    border border-susecondary dark:border-gray-800">
        <div class="mt-0 text-left">
            @can('access cp')
                <a href="/cp/collections/itnews" aria-label="IT news admin" class="float-right hover:border-blue-600">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.109 17H1v-2a4 4 0 0 1 4-4h.87M10 4.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm7.95 2.55a2 2 0 0 1 0 2.829l-6.364 6.364-3.536.707.707-3.536 6.364-6.364a2 2 0 0 1 2.829 0Z"/>
                    </svg>
                </a>
            @endif
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">
                {{__("Information from DSV IT")}}
            </h3>
            @nocache('home.partials.itnews')
        </div>
    </div>
    <div class="absolute top-0 end-0 -z-[1] w-70 h-auto">
        <svg width="384" height="268" viewBox="0 0 384 268" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_f_6966_190390)">
                <rect x="200.667" y="-57" width="240.294" height="124.936" fill="#E2CCFF" fill-opacity="0.35"></rect>
            </g>
            <defs>
                <filter id="filter0_f_6966_190390" x="0.666687" y="-257" width="640.294" height="524.936" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                    <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_6966_190390"></feGaussianBlur>
                </filter>
            </defs>
        </svg>
    </div>
</div>
