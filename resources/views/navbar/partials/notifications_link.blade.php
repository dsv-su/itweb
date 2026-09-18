@php($notificationTooltipId = $notificationTooltipId ?? 'notifications-tooltip')
<a href="{{ app()->getLocale() === 'sv' ? route('notifications.localized', ['lang' => 'swe']) : route('notifications') }}"
   class="{{ $notificationLinkClasses }} relative shrink-0"
    data-dashboard-tooltip="{{ $notificationTooltipId }}"
    aria-describedby="{{ $notificationTooltipId }}"
   @if(request()->routeIs('notifications', 'notifications.localized')) aria-current="page" @endif>
    <svg class="h-6 w-6 text-gray-800 dark:text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="sr-only">{{ __('Notifications') }}</span>
    <livewire:indicator />
</a>
<div id="{{ $notificationTooltipId }}" role="tooltip" class="invisible absolute z-50 rounded-lg bg-gray-900 px-3 py-2 text-sm text-white opacity-0 dark:bg-gray-700">
    {{ __('Notifications') }}
</div>
