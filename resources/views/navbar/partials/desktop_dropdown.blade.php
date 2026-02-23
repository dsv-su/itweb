<div x-ref="navigationDropdown" x-show="navigationMenuOpen"
     x-transition:enter="transition ease-out duration-100"
     x-transition:enter-start="opacity-0 scale-90"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-90"
     @mouseover="navigationMenuClearCloseTimeout()" @mouseleave="navigationMenuLeave()"
     class="absolute top-0 pt-3 -translate-x-1/2 translate-y-11 hidden md:block" x-cloak>
    <div class="flex justify-center w-auto h-auto overflow-hidden bg-white border rounded-md shadow-sm border-neutral-200/70">
        <!-- Dropdown content for "Navigation" -->
        <div x-show="navigationMenu == 'getting-started'" class="flex items-stretch justify-center w-full max-w-2xl p-6 gap-x-3">

            {{--}}<div class="flex-shrink-0 w-48 rounded pt-28 pb-7 bg-gradient-to-br from-neutral-800 to-black">
                <div class="relative px-7 space-y-1.5 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span class="block font-bold">Budget Templates</span>
                    <span class="block text-sm opacity-60">Resources for Download</span>
                </div>
            </div>{{--}}

            <div class="w-72">
                <a  href="{{route('budget.template', 'eng')}}" @click="navigationMenuClose()"
                   class="block px-3.5 py-3 text-sm rounded
                          hover:bg-neutral-100 dark:hover:bg-white/10
                          transition-colors duration-150">
                    <span class="block mb-1 font-medium text-gray-900 dark:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        {{ __("DSV Standard Budget (English)") }}
                    </span>

                    <span class="block font-light leading-5 text-gray-600 dark:text-gray-600">
                        {{__('Template for all project applications')}}
                    </span>

                </a>
                <a  href="{{route('budget.template', 'swe')}}" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded hover:bg-neutral-100">
                    <span class="block mb-1 font-medium text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        {{ __("DSV Standard Budget (Swedish)") }}
                    </span>
                    <span class="block leading-5 opacity-50">
                        {{__('Template for all project applications')}}
                    </span>
                </a>
                <a  href="{{route('budget.template', 'eu')}}" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded hover:bg-neutral-100">
                    <span class="block mb-1 font-medium text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        {{ __("DSV Standard Budget EU") }}
                    </span>
                    <span class="block leading-5 opacity-50">
                        {{__('Template for Horizon EU project applications')}}
                    </span>
                </a>
            </div>
        </div>
        <!-- Dropdown content for "Help Guides" -->
        <div x-show="navigationMenu == 'learn-more'" class="flex items-stretch justify-center w-full p-6">
            <div class="w-72">
                <a href="{{ route('usermanual') }}" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded hover:bg-neutral-100">
                    <span class="block mb-1 font-medium text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        User Guide
                    </span>
                    <span class="block font-light leading-5 opacity-50">Getting started: How to submit a proposal</span>
                </a>
                <a href="#" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded hover:bg-neutral-100">
                    <span class="block mb-1 font-medium text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Financial User Guide
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>

                    </span>
                    <span class="block font-light leading-5 opacity-50">Guide to Financial Procedures</span>
                </a>
                <a href="#" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded hover:bg-neutral-100">
                    <span class="block mb-1 font-medium text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Settings Guide
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block mb-2 size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </span>
                    <span class="block leading-5 opacity-50">Configure Settings</span>
                </a>
            </div>
        </div>
    </div>
</div>
