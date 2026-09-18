<div class="relative shrink-0">
    @php
        $searchUrl = app()->getLocale() === 'sv'
            ? route('search.localized', ['lang' => 'swe'])
            : route('search');
    @endphp

    <a
        data-dashboard-tooltip="{{ $searchTooltipId }}"
        href="{{ $searchUrl }}"
        class="grid h-11 w-11 place-items-center rounded-lg text-gray-600 transition hover:bg-gray-100 hover:text-gray-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white dark:focus-visible:ring-blue-300 dark:focus-visible:ring-offset-gray-900 md:h-11 md:w-11"
        aria-label="{{ __('Open search') }}"
        aria-describedby="{{ $searchTooltipId }}"
    >
        <svg class="h-5 w-5 md:h-6 md:w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            </path>
        </svg>
    </a>

    <div id="{{ $searchTooltipId }}" role="tooltip" class="invisible absolute z-50 rounded-lg bg-gray-900 px-3 py-2 text-sm text-white opacity-0 dark:bg-gray-700" data-popper-placement="top">
        {{ __("Search") }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</div>
