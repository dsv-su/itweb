<div x-show="mobileMenuOpen" class="md:hidden">
    <ul class="px-2 pt-2 pb-3 space-y-1">
        <!-- Mobile Dropdown for "Navigation" -->
        <li>
            <button @click="activeMobileMenu = activeMobileMenu === 'getting-started' ? '' : 'getting-started'" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                {{__("Budget Templates")}}
                <svg :class="{ 'rotate-180': activeMobileMenu === 'getting-started' }" class="inline-block h-4 w-4 ml-2 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="activeMobileMenu === 'getting-started'" class="pl-4 space-y-1" x-transition>
                <a href="{{route('budget.template', 'eng')}}" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    {{ __("DSV Standard Budget (English)") }}
                </a>
                <a href="{{route('budget.template', 'swe')}}" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    {{ __("DSV Standard Budget (Swedish)") }}
                </a>
                <a href="{{route('budget.template', 'eu')}}" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    {{ __("DSV Standard Budget EU") }}
                </a>
            </div>
        </li>
        <!-- Mobile Dropdown for "Manage" -->

            <li>
                <button @click="activeMobileMenu = activeMobileMenu === 'learn-more' ? '' : 'learn-more'" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    {{__("Help Guides")}}
                    <svg :class="{ 'rotate-180': activeMobileMenu === 'learn-more' }" class="inline-block h-4 w-4 ml-2 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeMobileMenu === 'learn-more'" class="pl-4 space-y-1" x-transition>
                    <a href="{{ route('usermanual') }}" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        User Guide
                    </a>
                    <a href="#" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Financial User Guide
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </a>
                    <a href="#_" @click="mobileMenuOpen = false; activeMobileMenu = ''" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Settings Guide
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </a>
                </div>
            </li>

        <li>
            <a href="{{route('pp.stats.committed')}}" class="inline-flex items-center justify-center px-3 h-10 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 dark:hover:text-gray-200">
                {{__("Stats")}}
            </a>
        </li>
        <li>
            <a href="{{ route('pp.create') }}"
               class="inline-flex items-center justify-center h-9 px-3 rounded-md font-medium text-sm
            bg-suprimary text-white shadow-sm
            hover:bg-blue-700
            focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2
            dark:bg-blue-500 dark:hover:bg-blue-400 dark:text-white
            dark:focus-visible:ring-blue-400 dark:focus-visible:ring-offset-neutral-900">
                {{ __("Create New Proposal") }}
            </a>
        </li>
        <!-- Admin -->
        @if(auth()->user()->isVice() || auth()->user()->isSuperAdmin())
            <li>
                <a href="{{route('vice_settings.index')}}" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    {{__("Settings")}}
                </a>
            </li>
        @endif
        @if( auth()->user()->isSuperAdmin())
            <li>
                <a href="{{route('admin.pp.index')}}" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-700 hover:bg-neutral-100 dark:text-white">
                    {{__("Admin")}}
                </a>
            </li>
        @endif
    </ul>
</div>

