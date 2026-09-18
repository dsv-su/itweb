<span class="pointer-events-none absolute right-0 top-0">
    @if(count($dashboard ?? []) > 0)
        <span aria-hidden="true" class="flex min-h-5 min-w-5 items-center justify-center rounded-full bg-blue-700 px-1 text-xs font-semibold leading-5 text-white ring-2 ring-white dark:ring-gray-900">{{ count($dashboard) > 99 ? '99+' : count($dashboard) }}</span>
        <span class="sr-only">{{ __('Notifications requiring attention') }}: {{ count($dashboard) }}</span>
    @endif
</span>
