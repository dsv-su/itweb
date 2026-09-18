@php($themeTooltipId = $themeTooltipId ?? 'theme-tooltip')
<!-- Theme toggle shared by the mobile and desktop toolbars. -->
<button
    type="button"
    data-toggle-dark="light"
    data-dashboard-tooltip="{{ $themeTooltipId }}"
    aria-describedby="{{ $themeTooltipId }}"
    aria-pressed="false"
    class="theme-toggle inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg text-gray-800 hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus-visible:outline-blue-300"
>
    <svg data-toggle-icon="moon"
         class="hidden w-5 h-5"
         aria-hidden="true"
         xmlns="http://www.w3.org/2000/svg"
         fill="currentColor"
         viewBox="0 0 18 20"
    >
        <path d="M17.8 13.75a1 1 0 0 0-.859-.5A7.488 7.488 0 0 1 10.52 2a1 1 0 0 0 0-.969A1.035 1.035 0 0 0 9.687.5h-.113a9.5 9.5 0 1 0 8.222 14.247 1 1 0 0 0 .004-.997Z"></path>
    </svg>
    <svg data-toggle-icon="sun"
         class="hidden w-5 h-5"
         aria-hidden="true"
         xmlns="http://www.w3.org/2000/svg"
         fill="currentColor"
         viewBox="0 0 20 20">
        <path d="M10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0-11a1 1 0 0 0 1-1V1a1 1 0 0 0-2 0v2a1 1 0 0 0 1 1Zm0 12a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0v-2a1 1 0 0 0-1-1ZM4.343 5.757a1 1 0 0 0 1.414-1.414L4.343 2.929a1 1 0 0 0-1.414 1.414l1.414 1.414Zm11.314 8.486a1 1 0 0 0-1.414 1.414l1.414 1.414a1 1 0 0 0 1.414-1.414l-1.414-1.414ZM4 10a1 1 0 0 0-1-1H1a1 1 0 0 0 0 2h2a1 1 0 0 0 1-1Zm15-1h-2a1 1 0 1 0 0 2h2a1 1 0 0 0 0-2ZM4.343 14.243l-1.414 1.414a1 1 0 1 0 1.414 1.414l1.414-1.414a1 1 0 0 0-1.414-1.414ZM14.95 6.05a1 1 0 0 0 .707-.293l1.414-1.414a1 1 0 1 0-1.414-1.414l-1.414 1.414a1 1 0 0 0 .707 1.707Z"></path>
    </svg>
    <span class="sr-only">{{ __('Toggle dark/light mode') }}</span>
</button>
<div id="{{ $themeTooltipId }}" role="tooltip" class="invisible absolute z-50 rounded-lg bg-gray-900 px-3 py-2 text-sm text-white opacity-0 dark:bg-gray-700">
    {{ __('Toggle dark/light mode') }}
</div>
