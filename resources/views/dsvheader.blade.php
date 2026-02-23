<style>
    /* Tooltips cause vertical scroll */
    [role="tooltip"] { position: fixed !important; }
</style>
<div class="w-full sm:block sm:w-auto overflow-x-hidden">
    <!-- Small breakpoint -->
    <div class="md:hidden block grid grid-cols-4 border-b-4 border-susecondary">
        <div class="flex items-center justify-center col-span-1 bg-suprimary">
            <!-- mobile logo -->
            <img class="md:hidden block h-12 m-3" src="{{asset('images/su_logo_no_text.svg')}}"  alt="Stockholms universitet">
        </div>
        <div class="items-center inline-flex justify-start col-span-3 sm:flex px-5 bg-sudepartment">
            <span class="self-center text-base font-normal font-sudepartment whitespace-pre-line text-white dark:text-white">{{__("Department of Computer and Systems Sciences")}}</span>
        </div>
    </div>
    <!-- Medium and large breakpoint -->
    <div class="hidden md:block md:grid grid-cols-4 border-b-4 border-susecondary">
        <div class="flex items-center justify-end col-span-1 bg-suprimary">
            <div class="col-span-1 col-span-1 md:opacity-100 opacity-0">
                <img class="w-44 mr-3" src="{{asset('images/su_swe.png')}}" alt="Stockholms universitet">
            </div>
        </div>

        <div class="items-center inline-flex justify-start hidden col-span-2 sm:flex pl-5 bg-sudepartment">
            <span class="self-center text-2xl font-normal font-sudepartment whitespace-pre-line text-white dark:text-white">{{__("Department of Computer and Systems Sciences")}}</span>
        </div>

        <div class="md:opacity-100 opacity-0 flex items-center justify-end col-span-1 bg-sudepartment px-2">
            <!-- User displayName -->
            @include('partials.displayname')

            <!-- Dark mode switcher -->
            <button id="theme-toggle" data-tooltip-target="navbar-dropdown-toggle-dark-mode-tooltip" type="button" data-toggle-dark="light" class="flex items-center w-6 h-6 justify-center text-xs font-small text-white outline outline-offset-2 outline-1 rounded-sm toggle-dark-state-example hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500 focus:outline-none dark:text-gray-200 dark:border-gray-200 dark:hover:text-white dark:hover:bg-gray-700">
                <svg id="theme-toggle-dark-icon" data-toggle-icon="moon" class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                    <path d="M17.8 13.75a1 1 0 0 0-.859-.5A7.488 7.488 0 0 1 10.52 2a1 1 0 0 0 0-.969A1.035 1.035 0 0 0 9.687.5h-.113a9.5 9.5 0 1 0 8.222 14.247 1 1 0 0 0 .004-.997Z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" data-toggle-icon="sun" class="hidden w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0-11a1 1 0 0 0 1-1V1a1 1 0 0 0-2 0v2a1 1 0 0 0 1 1Zm0 12a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0v-2a1 1 0 0 0-1-1ZM4.343 5.757a1 1 0 0 0 1.414-1.414L4.343 2.929a1 1 0 0 0-1.414 1.414l1.414 1.414Zm11.314 8.486a1 1 0 0 0-1.414 1.414l1.414 1.414a1 1 0 0 0 1.414-1.414l-1.414-1.414ZM4 10a1 1 0 0 0-1-1H1a1 1 0 0 0 0 2h2a1 1 0 0 0 1-1Zm15-1h-2a1 1 0 1 0 0 2h2a1 1 0 0 0 0-2ZM4.343 14.243l-1.414 1.414a1 1 0 1 0 1.414 1.414l1.414-1.414a1 1 0 0 0-1.414-1.414ZM14.95 6.05a1 1 0 0 0 .707-.293l1.414-1.414a1 1 0 1 0-1.414-1.414l-1.414 1.414a1 1 0 0 0 .707 1.707Z"></path>
                </svg>
                <span class="sr-only">Toggle dark/light mode</span>
            </button>

            <!-- Language switcher -->
            @include('partials.language')
            <!-- end language switcher -->
            <!-- Dashbord -->
            @include('partials.dashboard-access')

            <!-- Tooltips -->
            @include('partials.dsvheader-tooltips')

        </div>
    </div>
</div>

