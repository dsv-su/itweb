<!-- User displayName -->
<div class="relative inline-flex">
    <button
        type="button"
        data-tooltip-target="displayName-tooltip"
        class="flex items-center w-44 h-6 px-3 justify-center text-xs font-small text-white rounded-lg toggle-dark-state-example
           hover:text-white focus-visible:z-10 focus-visible:ring-2 focus-visible:ring-gray-300 dark:focus-visible:ring-gray-500
           focus-visible:outline-none dark:text-gray-200 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
        aria-describedby="displayName-tooltip"
        aria-label="Signed in user"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-4 h-4 mr-2"
            aria-hidden="true"
            focusable="false"
        >
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>

        <span class="truncate">
      {{ auth()->user()->name ?? 'Not logged in' }}
    </span>
    </button>

</div>



