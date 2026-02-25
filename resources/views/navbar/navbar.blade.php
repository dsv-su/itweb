<nav class="bg-white border-b border-susecondary dark:bg-gray-900 dark:border-gray-700">
    <div class="relative flex flex-wrap md:flex-row lg:flex-nowrap w-full p-3 mx-auto bg-white items-center justify-between md:px-6 lg:px-8 dark:border-gray-600 dark:bg-gray-900">
        <a href="{{ config('app.url') }}" class="flex items-center mr-4">
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

        <!-- Mobile -->
        <!-- Notifications -->
        <div class="flex">
            <livewire:mobileindicator />
            <button @click="open = !open" data-tooltip-target="workflow-notification-tooltip" type="button" data-dropdown-toggle="notification-dropdown"
                    class="md:hidden p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400
                    dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                <span class="sr-only">View notifications</span>
                <!-- Bell icon -->
                <svg class="w-6 h-6 text-black dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 21">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M8 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C15 15.4 15 16 14.462 16H1.538C1 16 1 15.4 1 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 8 3.464ZM4.54 16a3.48 3.48 0 0 0 6.92 0H4.54Z"/>
                </svg>
            </button>
        </div>
        <!-- E-forms -->
        <button @click="open = !open" data-tooltip-target="workflow-requests-tooltip" type="button" data-dropdown-toggle="apps-dropdown"
                class="md:hidden p-2 text-black rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400
                dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
            <span class="sr-only">View notifications</span>
            <!-- Icon -->
            <svg style="fill:gray" class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
            </svg>
        </button>
        <!-- Hamburger nav -->
        <button data-collapse-toggle="navbar-multi-level" type="button"
                id="menuBtn"
                class="hamburger block md:hidden focus:outline-none"
                onclick="navToggle();"
                {{--}}
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-black rounded-lg md:hidden hover:bg-gray-100
                focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                {{--}}
                aria-controls="navbar-multi-level" aria-expanded="false">
            <span class="hamburger_sub_top"></span><span class="hamburger_sub_bottom"></span>
            <span class="sr-only">Open main menu</span>
            {{--}}
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
            {{--}}
        </button>

        @antlers
        <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-susecondary rounded-lg bg-gray-50 md:space-x-8
            rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            {{ if nav_exists:itweb }}
                {{ nav:itweb  }}
                <!-- Children -->
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
                        <!-- Dropdown menu -->
                        <div id="dropdownNavbar-{{ title }}"
                             class="absolute top-full left-0 z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-full md:w-auto dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="border border-susecondary rounded-lg py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLargeButton">
                            {{ children }}
                            <!-- Depth 1 -->
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
                                            <!-- Depth 2 -->
                                            {{ if children }}
                                            <li aria-labelledby="dropdownNavbarLink">
                                                <button id="trippleDropdownButton-{{ title }}" data-dropdown-toggle="trippleDropdown-{{ depth }}-{{ title }}"
                                                        type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    {{ title }}
                                                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                    </svg>
                                                </button>
                                                <div id="trippleDropdown-{{depth}}-{{ title }}" {{--}}class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"{{--}}class="absolute top-0 left-full z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-full md:w-auto dark:bg-gray-700">
                                                    <ul class="border border-susecondary rounded-lg py-2 pl-9 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="trippleDropdownButton-{{ title }}">
                                                        {{ children}}
                                                        <!-- Depth 3 -->
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
                <!-- No children -->
                    <li>
                        <a href="{{ url }}" aria-label="Nav link"
                           class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent"
                           aria-current="page">{{ title }}</a>
                    </li>
                    {{ /if }}
                    {{ /nav:itweb }}
                {{ /if }}
            </ul>
        </div>
        @endantlers
        {{--}}<livewire:search />{{--}}
        @include('navbar.dashboard')
    </div>

</nav>
<script>
    // Open close mobile menu
    function navToggle() {
        var btn = document.getElementById('menuBtn');
        var search = document.getElementById('searchinput');

        btn.classList.toggle('open');
        search.classList.toggle('hidden');
    }
</script>
