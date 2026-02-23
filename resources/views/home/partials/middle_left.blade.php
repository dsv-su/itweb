<div class="md:order-2 text-left relative border border-susecondary dark:border-gray-800 rounded-xl {{--}}md:min-h-[230px]{{--}} ">
    {{--}}<div class="relative overflow-hidden w-full h-full rounded-xl">{{--}}
        <div class="p-6 flex flex-col {{--}}md:min-h-[480px]{{--}} md:h-fit rounded-xl dark:border-gray-700">
            <div class="mt-0 text-left">
                @can('access cp')
                    <a href="/cp/collections/news" aria-label="Internal news admin" class="float-right hover:border-blue-600">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.109 17H1v-2a4 4 0 0 1 4-4h.87M10 4.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm7.95 2.55a2 2 0 0 1 0 2.829l-6.364 6.364-3.536.707.707-3.536 6.364-6.364a2 2 0 0 1 2.829 0Z"/>
                        </svg>
                    </a>
                @endif
                <h3 class="text-left text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">
                    {{__("Internal information")}}
                </h3>
                @nocache('home.partials.internal')
            </div>

        </div>
    {{--}}</div>{{--}}
</div>
