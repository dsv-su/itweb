<nav class="bg-white border-b border-susecondary dark:bg-gray-900 dark:border-gray-700">
    <div class="relative flex flex-nowrap w-full h-14 px-3 mx-auto bg-white items-center justify-between
            md:px-6 lg:px-8 dark:border-gray-600 dark:bg-gray-900 overflow-visible"
            >
        <a href="{{ config('app.url') }}" class="flex items-center mr-4 min-w-0">
            <div class="flex items-center opacity-90 h-full ml-2 dark:text-white">
                <span class="px-1.5 py-1 text-xl leading-none border-2 border-suprimary rounded-lg">
                    DSV
                </span>
                <span class="ml-1 mb-1 text-xl font-sudepartment font whitespace-nowrap">
                    {{ __("it") }}
                </span>
            </div>

            @if(config('app.name') == 'itDev')
                <span class="hidden md:block font-rock text-lg whitespace-nowrap dark:text-white">Dev</span>
            @endif
        </a>

        <!-- Mobile actions -->
        <div class="flex items-center gap-1 md:hidden shrink-0">
            <livewire:mobileindicator />

            <!-- Mobile Notifications (own dropdown, not inside md:block containers) -->
            <div class="relative">
                <button
                    data-tooltip-target="workflow-notification-tooltip"
                    type="button"
                    data-dropdown-toggle="notification-dropdown-mobile"
                    data-dropdown-placement="bottom-end"
                    class="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400
                           dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                >
                    <span class="sr-only">View notifications</span>
                    <svg class="w-6 h-6 text-black dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 21">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M8 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C15 15.4 15 16 14.462 16H1.538C1 16 1 15.4 1 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 8 3.464ZM4.54 16a3.48 3.48 0 0 0 6.92 0H4.54Z"/>
                    </svg>
                </button>

                <!-- Mobile notifications dropdown -->
                <div
                    id="notification-dropdown-mobile"
                    class="hidden fixed left-0 right-0 top-14 z-50 w-screen max-w-none text-base list-none bg-white rounded-lg
                           divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700"
                >
                    <livewire:notificationstoggler />
                    <div>
                        <livewire:requestnotifications />
                        <livewire:returnednotifications />
                    </div>
                    <livewire:userrequeststoggler />
                    <div>
                        <livewire:usernotifications />
                    </div>
                </div>
            </div>

            <!-- Mobile E-forms (own dropdown) -->
            <div class="relative">
                <button
                    data-tooltip-target="workflow-requests-tooltip"
                    type="button"
                    data-dropdown-toggle="apps-dropdown-mobile"
                    data-dropdown-placement="bottom-end"
                    class="p-2 text-black rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400
                           dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                >
                    <span class="sr-only">Open e-services</span>
                    <svg style="fill:gray" class="w-4 h-4" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                    </svg>
                </button>

                <!-- Mobile apps dropdown -->
                <div
                    id="apps-dropdown-mobile"
                    class="hidden fixed left-0 right-0 top-14 z-50 w-screen max-w-none text-base list-none bg-white rounded-lg
                            max-h-[calc(100vh-4rem)] overflow-y-auto divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600"
                >
                    <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                        {{ __("Available e-services") }}
                    </div>
                    <div class="grid grid-cols-3 gap-4 p-4">
                        <a href="{{ route('travel-request-create') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                            <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 group-hover:text-white dark:text-white dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9V4a3 3 0 0 0-6 0v5m9.92 10H2.08a1 1 0 0 1-1-1.077L2 6h14l.917 11.923A1 1 0 0 1 15.92 19Z"/>
                            </svg>
                            <div class="text-sm font-medium text-blue-600 dark:text-white">{{ __("Travel Request") }}</div>
                        </a>

                        @if(\App\Models\SettingsOh::first()->form_enable)
                            <a href="{{ route('pp.show', 'my') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                                <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h4M9 3v4a1 1 0 0 1-1 1H4m11 6v4m-2-2h4m3 0a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                                </svg>
                                <div class="text-sm font-medium text-blue-600 dark:text-white">Project Proposals</div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <button
                data-collapse-toggle="navbar-multi-level"
                type="button"
                id="menuBtn"
                class="hamburger block md:hidden focus:outline-none"
                onclick="navToggle();"
                aria-controls="navbar-multi-level"
                aria-expanded="false"
            >
                <span class="hamburger_sub_top"></span><span class="hamburger_sub_bottom"></span>
                <span class="sr-only">Open main menu</span>
            </button>
        </div>

        @antlers
        <div
             class="hidden md:block md:static md:w-auto absolute left-0 top-full w-full z-40 bg-white dark:bg-gray-900
                    {{--}}border-t border-susecondary{{--}} dark:border-gray-700"
             id="navbar-multi-level"
        >
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-susecondary rounded-lg bg-gray-50 md:space-x-8
            rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

                {{ nav:main  }}
                {{ if children }}
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar-{{ title }}"
                            class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0
                            md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        {{ title }}
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>

                    <div id="dropdownNavbar-{{ title }}"
                         class="absolute top-full left-0 z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-full md:w-auto dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="border border-susecondary rounded-lg py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLargeButton">
                            {{ children }}
                            {{ if children }}
                            <li aria-labelledby="dropdownNavbarLink">
                                <button id="doubleDropdownButton-{{ title }}" data-dropdown-toggle="doubleDropdown-{{ depth }}-{{ title }}" type="button"
                                        class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    {{ title }}
                                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                    </svg>
                                </button>
                                <div id="doubleDropdown-{{depth}}-{{ title }}"
                                     class="absolute top-0 left-full z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-full md:w-auto dark:bg-gray-700">
                                    <ul class="border border-susecondary rounded-lg py-2 pl-6 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="doubleDropdownButton-{{ title }}">
                                        {{ children}}
                                        {{ if children }}
                                        <li aria-labelledby="dropdownNavbarLink">
                                            <button id="trippleDropdownButton-{{ title }}" data-dropdown-toggle="trippleDropdown-{{ depth }}-{{ title }}"
                                                    type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ title }}
                                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                </svg>
                                            </button>
                                            <div id="trippleDropdown-{{depth}}-{{ title }}" class="absolute top-0 left-full z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-full md:w-auto dark:bg-gray-700">
                                                <ul class="border border-susecondary rounded-lg py-2 pl-9 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="trippleDropdownButton-{{ title }}">
                                                    {{ children}}
                                                    {{ if children }}
                                                    <li aria-labelledby="dropdownNavbarLink">
                                                        <button id="lastDropdownButton-{{ title }}" data-dropdown-toggle="lastDropdown-{{ depth }}-{{ title }}" data-dropdown-placement="right-start" type="button"
                                                                class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            {{ title }}
                                                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                            </svg>
                                                        </button>
                                                    </li>
                                                    {{ else }}
                                                    <li>
                                                        <a href="{{ url }}" aria-label="Nav link" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                            {{ title}}
                                                        </a>
                                                    </li>
                                                    {{ /if }}
                                                    {{ /children }}
                                                </ul>
                                            </div>
                                        </li>
                                        {{ else }}
                                        <li>
                                            <a href="{{ url }}" aria-label="Nav link" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                {{ title}}
                                            </a>
                                        </li>
                                        {{ /if }}
                                        {{ /children }}
                                    </ul>
                                </div>
                            </li>
                            {{ else }}
                            <li>
                                <a href="{{ url }}" aria-label="Nav link" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ title }}</a>
                            </li>
                            {{ /if }}
                            {{ /children }}
                        </ul>
                    </div>
                </li>
                {{ else }}
                <li>
                    <a href="{{ url }}" aria-label="Nav link"
                       class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent"
                       aria-current="page">{{ title }}</a>
                </li>
                {{ /if }}
                {{ /nav:main }}

            </ul>
        </div>
        @endantlers

        @include('navbar.dashboard')
    </div>
</nav>

<script>
    function navToggle() {
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('navbar-multi-level');

        btn.classList.toggle('open');
        menu.classList.toggle('hidden'); // ✅ actually show/hide the menu
    }
</script>
