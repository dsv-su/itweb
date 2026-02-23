<ul class="hidden md:flex items-center justify-center flex-1 p-1 space-x-1 list-none space-x-1 text-neutral-700 group dark:text-white">
    <li>
        <button
            :class="{ 'bg-neutral-100': navigationMenu=='getting-started', 'hover:bg-neutral-100': navigationMenu!='getting-started' }"
            @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='getting-started'"
            @mouseleave="navigationMenuLeave()"
            class="inline-flex items-center justify-center h-10 px-4 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 focus:outline-none">
            <span>{{__("Budget Templates")}}</span>
            <svg :class="{ '-rotate-180': navigationMenuOpen && navigationMenu=='getting-started' }"
                 class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </li>

        <li>
            <button
                :class="{ 'bg-neutral-100': navigationMenu=='learn-more', 'hover:bg-neutral-100': navigationMenu!='learn-more' }"
                @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='learn-more'"
                @mouseleave="navigationMenuLeave()"
                class="inline-flex items-center justify-center h-10 px-4 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 focus:outline-none">
                <span>{{__("Help Guides")}}</span>
                <svg :class="{ '-rotate-180': navigationMenuOpen && navigationMenu=='learn-more' }"
                     class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </li>

    <li>

        <a href="{{route('pp.stats.committed')}}" class="inline-flex items-center justify-center h-10 px-4 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 dark:hover:text-gray-200">
            <svg class="inline shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
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

    @if(auth()->user()->isVice() || auth()->user()->isSuperAdmin())
        <li>
            <a href="{{route('vice_settings.index')}}" class="inline-flex items-center justify-center h-10 px-4 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 dark:hover:text-gray-200">
                <svg class="inline shrink-0 size-4" text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                </svg>
                {{__("Settings")}}
            </a>
        </li>
    @endif
    @if( auth()->user()->isSuperAdmin())
        <li>
            <a  href="{{route('admin.pp.index')}}" class="inline-flex items-center justify-center h-10 px-4 py-2 font-medium transition-colors rounded-md hover:text-neutral-900 dark:hover:text-gray-200">
                {{__("Admin")}}
            </a>
        </li>
    @endif
</ul>
