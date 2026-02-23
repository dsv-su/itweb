@can('access cp')
    @if(auth()->user()->isSuperAdmin())
        <a
            data-tooltip-target="navbar-dashboard-tooltip"
            href="/cp"
            class="inline-flex items-center justify-center w-8 h-8 rounded text-white
                   hover:bg-white/10
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white/70
                   focus-visible:ring-offset-sudepartment"
            aria-label="Control panel"
        >
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.3 4.8 2.9 2.9M7 7H4a1 1 0 0 0-1 1v10c0 .6.4 1 1 1h11c.6 0 1-.4 1-1v-4.5m2.4-10a2 2 0 0 1 0 3l-6.8 6.8L8 14l.7-3.6 6.9-6.8a2 2 0 0 1 2.8 0Z"/>
            </svg>
        </a>
    @endif
@endcan
