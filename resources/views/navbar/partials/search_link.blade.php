<div class="relative ml-2 mr-1 md:order-0">
    @php
        $searchUrl = app()->getLocale() === 'sv'
            ? route('search.localized', ['lang' => 'swe'])
            : route('search');
    @endphp

    <a
        data-tooltip-target="search-tooltip"
        href="{{ $searchUrl }}"
        class="grid h-10 w-10 place-items-center rounded-lg text-gray-600 transition hover:bg-gray-100 hover:text-gray-950 focus:outline-none focus-visible:ring-4 focus-visible:ring-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white dark:focus-visible:ring-gray-600 md:h-11 md:w-11"
        aria-label="{{ __('Open search') }}"
        aria-describedby="search-tooltip"
    >
        <svg class="h-5 w-5 md:h-6 md:w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            </path>
        </svg>
    </a>

    <div id="search-tooltip" role="tooltip" class="{{ $tooltipClasses }}" data-popper-placement="top">
        {{ __("Search") }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</div>
