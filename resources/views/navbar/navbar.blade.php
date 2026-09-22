@php
    $homeEntry = \Statamic\Facades\Entry::query()
        ->where('collection', 'pages')
        ->where('slug', 'home')
        ->first();

    $localizedHome = $homeEntry ? $homeEntry->in(\Statamic\Facades\Site::current()->handle()) : null;
    $projectProposalsEnabled = (bool) \App\Models\SettingsOh::query()->value('form_enable');

    $focusClasses = 'focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 dark:focus-visible:ring-blue-300 dark:focus-visible:ring-offset-gray-900';
    $navLinkClasses = 'flex min-h-11 items-center py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:px-0 md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent md:dark:hover:text-blue-500 '.$focusClasses;
    $dropdownButtonClasses = 'flex min-h-11 items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white '.$focusClasses;
    $dropdownLinkClasses = 'block min-h-11 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white '.$focusClasses;
    $mobileActionButtonClasses = 'inline-flex shrink-0 items-center justify-center h-11 w-11 p-2 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-700 '.$focusClasses;
    $mobileDropdownClasses = 'hidden fixed left-0 right-0 top-14 z-50 w-screen max-w-none text-base list-none bg-white rounded-lg divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600';
    $serviceLinkClasses = 'block min-h-11 p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group '.$focusClasses;
    $serviceIconClasses = 'mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-white';
    $serviceLabelClasses = 'text-sm font-medium text-blue-600 dark:text-white';
@endphp

<nav id="site-navigation" aria-label="{{ __('Main navigation') }}" class="bg-white border-b border-susecondary dark:bg-gray-900 dark:border-gray-700">
    <div class="relative flex flex-nowrap items-center justify-between w-full min-h-14 px-2 mx-auto overflow-visible bg-white md:px-6 lg:px-8 dark:border-gray-600 dark:bg-gray-900">
        @if($localizedHome)
            <a href="{{ $localizedHome->url() }}" aria-label="{{ __('DSV IT home') }}" class="flex items-center min-w-0 shrink-0 mr-1 sm:mr-4 {{ $focusClasses }}">
                <div class="flex items-center h-full sm:ml-2 opacity-90 dark:text-white">
                    <span class="px-1.5 py-1 text-xl leading-none border-2 border-black rounded-lg dark:border-white">
                        DSV
                    </span>
                    <span class="hidden sm:inline ml-1 mb-1 text-xl font-sudepartment font whitespace-nowrap">
                        {{ __("it") }}
                    </span>
                </div>

                @if(config('app.name') === 'itDev')
                    <span class="hidden text-lg font-rock whitespace-nowrap md:block dark:text-white">Dev</span>
                @endif
            </a>
        @endif

        <div class="flex items-center gap-1 shrink-0 md:hidden">
            @include('navbar.partials.search_link', ['searchTooltipId' => 'search-tooltip-mobile'])
            @include('navbar.partials.dark_toggle', ['themeTooltipId' => 'theme-tooltip-mobile'])

            <div class="relative">
                @include('navbar.partials.notifications_link', ['notificationLinkClasses' => $mobileActionButtonClasses, 'notificationTooltipId' => 'notifications-tooltip-mobile'])
            </div>

            <div class="relative">
                <button
                    data-dashboard-tooltip="workflow-requests-tooltip-mobile"
                    type="button"
                    data-dropdown-toggle="apps-dropdown-mobile"
                    data-dropdown-placement="bottom-end"
                    class="{{ $mobileActionButtonClasses }} text-black dark:text-gray-200"
                    aria-expanded="false"
                    aria-controls="apps-dropdown-mobile"
                >
                    <span class="sr-only">{{ __("Open e-services") }}</span>
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                    </svg>
                </button>

                <div id="apps-dropdown-mobile" class="{{ $mobileDropdownClasses }} max-h-[calc(100vh-4rem)] overflow-y-auto">
                    <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                        {{ __("Available e-services") }}
                    </div>

                    <div class="grid grid-cols-2 gap-2 p-4">
                        <a href="{{ app()->getLocale() === 'sv' ? url('/swe/travel') : route('travel-request-create') }}" class="{{ $serviceLinkClasses }}">
                            <svg class="{{ $serviceIconClasses }} group-hover:text-blue-800 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9V4a3 3 0 0 0-6 0v5m9.92 10H2.08a1 1 0 0 1-1-1.077L2 6h14l.917 11.923A1 1 0 0 1 15.92 19Z"/>
                            </svg>
                            <div class="{{ $serviceLabelClasses }}">{{ __("Travel Request") }}</div>
                        </a>

                        @include('navbar.partials.travel_statistics_link')

                        @if($projectProposalsEnabled)
                            <a href="{{ route('pp.show', 'my') }}" class="{{ $serviceLinkClasses }}">
                                <svg class="{{ $serviceIconClasses }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h4M9 3v4a1 1 0 0 1-1 1H4m11 6v4m-2-2h4m3 0a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                                </svg>
                                <div class="{{ $serviceLabelClasses }}">{{ __("Project Proposals") }}</div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <button
                data-collapse-toggle="navbar-multi-level"
                type="button"
                id="menuBtn"
                class="relative flex shrink-0 items-center justify-center hamburger md:hidden {{ $focusClasses }}"
                aria-controls="navbar-multi-level"
                    aria-expanded="false"
            >
                <span class="hamburger_sub_top"></span>
                <span class="hamburger_sub_bottom"></span>
                <span class="sr-only">{{ __("Open main menu") }}</span>
            </button>
        </div>

        <div id="workflow-requests-tooltip-mobile" role="tooltip" class="invisible absolute z-50 rounded-lg bg-gray-900 px-3 py-2 text-sm text-white opacity-0 dark:bg-gray-700">
            {{ __('Services') }}
        </div>

        @antlers
            <div
                id="navbar-multi-level"
                class="hidden absolute left-0 top-full w-full z-40 bg-white dark:bg-gray-900 dark:border-gray-700
                       md:static md:z-auto md:flex md:flex-1 md:min-w-0 md:justify-center md:items-center md:w-auto"
            >
                <ul class="flex flex-col p-4 mt-4 font-medium bg-gray-50 border border-susecondary rounded-lg md:p-0 md:mt-0 md:flex-row md:items-center md:gap-8 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    {{ nav:main }}
                    {{ if children }}
                    <li class="relative flex items-center">
                        <button
                            id="dropdownNavbarLink-{{ id }}"
                            data-dropdown-toggle="dropdownNavbar-{{ id }}"
                            type="button"
                    aria-expanded="false"
                            aria-controls="dropdownNavbar-{{ id }}"
                            class="flex min-h-11 items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent {{ $focusClasses }}"
                        >
                            {{ title }}
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>

                        <div id="dropdownNavbar-{{ id }}" class="absolute top-full left-0 z-10 hidden w-full font-normal bg-white divide-y divide-gray-100 rounded-lg shadow md:w-80 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 border border-susecondary rounded-lg dark:text-gray-200" aria-labelledby="dropdownNavbarLink-{{ id }}">
                                {{ children }}
                                {{ if children }}
                                <li>
                                    <button id="doubleDropdownButton-{{ id }}" data-dropdown-toggle="doubleDropdown-{{ id }}" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="doubleDropdown-{{ id }}" class="{{ $dropdownButtonClasses }}">
                                        {{ title }}
                                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                        </svg>
                                    </button>

                                    <div id="doubleDropdown-{{ id }}" class="absolute top-0 left-full z-10 hidden w-full bg-white divide-y divide-gray-100 rounded-lg shadow md:w-80 dark:bg-gray-700">
                                        <ul class="py-2 pl-6 text-sm text-gray-700 border border-susecondary rounded-lg dark:text-gray-200" aria-labelledby="doubleDropdownButton-{{ id }}">
                                            {{ children }}
                                            {{ if children }}
                                            <li>
                                                <button id="trippleDropdownButton-{{ id }}" data-dropdown-toggle="trippleDropdown-{{ id }}" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="trippleDropdown-{{ id }}" class="{{ $dropdownButtonClasses }}">
                                                    {{ title }}
                                                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                    </svg>
                                                </button>

                                                <div id="trippleDropdown-{{ id }}" class="absolute top-0 left-full z-10 hidden w-full bg-white divide-y divide-gray-100 rounded-lg shadow md:w-80 dark:bg-gray-700">
                                                    <ul class="py-2 pl-9 text-sm text-gray-700 border border-susecondary rounded-lg dark:text-gray-200" aria-labelledby="trippleDropdownButton-{{ id }}">
                                                        {{ children }}
                                                        {{ if children }}
                                                        <li>
                                                            <button id="lastDropdownButton-{{ id }}" data-dropdown-toggle="lastDropdown-{{ id }}" data-dropdown-placement="right-start" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="lastDropdown-{{ id }}" class="{{ $dropdownButtonClasses }}">
                                                                {{ title }}
                                                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                                </svg>
                                                            </button>
                                                            <div id="lastDropdown-{{ id }}" class="absolute top-0 left-full z-10 hidden w-full bg-white rounded-lg shadow md:w-80 dark:bg-gray-700">
                                                                <ul class="py-2 pl-9 text-sm text-gray-700 border border-susecondary rounded-lg dark:text-gray-200" aria-labelledby="lastDropdownButton-{{ id }}">
                                                                    {{ children }}
                                                                    <li>
                                                                        <a href="{{ url }}" class="{{ $dropdownLinkClasses }}" {{ if is_current }}aria-current="page"{{ /if }}>{{ title }}</a>
                                                                    </li>
                                                                    {{ /children }}
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        {{ else }}
                                                        <li>
                                                            <a href="{{ url }}" class="{{ $dropdownLinkClasses }}" {{ if is_current }}aria-current="page"{{ /if }}>
                                                                {{ title }}
                                                            </a>
                                                        </li>
                                                        {{ /if }}
                                                        {{ /children }}
                                                    </ul>
                                                </div>
                                            </li>
                                            {{ else }}
                                            <li>
                                                <a href="{{ url }}" class="{{ $dropdownLinkClasses }}" {{ if is_current }}aria-current="page"{{ /if }}>
                                                    {{ title }}
                                                </a>
                                            </li>
                                            {{ /if }}
                                            {{ /children }}
                                        </ul>
                                    </div>
                                </li>
                                {{ else }}
                                <li>
                                    <a href="{{ url }}" class="{{ $dropdownLinkClasses }}" {{ if is_current }}aria-current="page"{{ /if }}>
                                        {{ title }}
                                    </a>
                                </li>
                                {{ /if }}
                                {{ /children }}
                            </ul>
                        </div>
                    </li>
                    {{ else }}
                    <li class="relative flex items-center">
                        <a href="{{ url }}" class="{{ $navLinkClasses }}" {{ if is_current }}aria-current="page"{{ /if }}>
                            {{ title }}
                        </a>
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
    (() => {
        const initNavbarToggle = () => {
            const btn = document.getElementById('menuBtn');
            const menu = document.getElementById('navbar-multi-level');

            if (!btn || !menu || btn.dataset.navbarToggleBound === 'true') {
                return;
            }

            btn.dataset.navbarToggleBound = 'true';

            const isOpen = () => !menu.classList.contains('hidden');

            const setOpen = (open) => {
                menu.classList.toggle('hidden', !open);
                btn.classList.toggle('open', open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            btn.addEventListener('click', (event) => {
                event.preventDefault();
                setOpen(!isOpen());
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && isOpen() && window.innerWidth < 768) {
                    setOpen(false);
                    btn.focus();
                }
            });

            document.addEventListener('click', (event) => {
                if (!isOpen() || btn.contains(event.target) || menu.contains(event.target)) {
                    return;
                }

                setOpen(false);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNavbarToggle);
        } else {
            initNavbarToggle();
        }

        document.addEventListener('livewire:navigated', initNavbarToggle);

        const initAccessibleDropdowns = () => {
            const nav = document.getElementById('site-navigation');
            if (!nav || nav.dataset.a11yDropdownsBound === 'true') return;

            nav.dataset.a11yDropdownsBound = 'true';

            const toggles = () => [...nav.querySelectorAll('[data-dropdown-toggle]')];
            const targetFor = (toggle) => document.getElementById(toggle.dataset.dropdownToggle);
            const focusables = (target) => [...target.querySelectorAll('a[href], button:not([disabled])')]
                .filter((item) => item.getClientRects().length > 0 && getComputedStyle(item).visibility !== 'hidden');
            const syncStates = () => toggles().forEach((toggle) => {
                const target = targetFor(toggle);
                if (target) toggle.setAttribute('aria-expanded', String(!target.classList.contains('hidden')));
            });

            const observer = new MutationObserver(syncStates);
            toggles().forEach((toggle) => {
                const target = targetFor(toggle);
                toggle.setAttribute('aria-haspopup', 'true');
                toggle.setAttribute('aria-controls', toggle.dataset.dropdownToggle);
                if (target) observer.observe(target, { attributes: true, attributeFilter: ['class'] });
            });
            syncStates();

            nav.addEventListener('keydown', (event) => {
                const toggle = event.target.closest('[data-dropdown-toggle]');
                const openTarget = toggles().map((item) => targetFor(item))
                    .find((target) => target && !target.classList.contains('hidden') && target.contains(event.target));

                if (toggle && ['ArrowDown', 'ArrowRight'].includes(event.key)) {
                    event.preventDefault();
                    const target = targetFor(toggle);
                    if (!target) return;
                    if (target.classList.contains('hidden')) toggle.click();
                    setTimeout(() => focusables(target)[0]?.focus(), 0);
                    return;
                }

                if (openTarget && ['ArrowDown', 'ArrowUp'].includes(event.key)) {
                    const items = focusables(openTarget);
                    const index = items.indexOf(event.target);
                    if (index === -1) return;
                    event.preventDefault();
                    const step = event.key === 'ArrowDown' ? 1 : -1;
                    items[(index + step + items.length) % items.length]?.focus();
                    return;
                }

                if (openTarget && ['Home', 'End'].includes(event.key)) {
                    const items = focusables(openTarget);
                    if (!items.length) return;
                    event.preventDefault();
                    items[event.key === 'Home' ? 0 : items.length - 1].focus();
                    return;
                }

                if (['Escape', 'ArrowLeft'].includes(event.key)) {
                    const openToggle = toggles().reverse().find((item) => {
                        const target = targetFor(item);
                        return target && !target.classList.contains('hidden') &&
                            (target.contains(event.target) || item === event.target);
                    });
                    if (openToggle) {
                        event.preventDefault();
                        openToggle.click();
                        openToggle.focus();
                    }
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAccessibleDropdowns);
        } else {
            initAccessibleDropdowns();
        }
        document.addEventListener('livewire:navigated', initAccessibleDropdowns);
    })();
</script>
