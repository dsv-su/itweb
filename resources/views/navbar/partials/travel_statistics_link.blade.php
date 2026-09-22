<a href="{{ app()->getLocale() === 'sv' ? route('travel-statistics.localized', ['lang' => 'swe']) : route('travel-statistics') }}" class="{{ $serviceLinkClasses }}">
    <svg class="{{ $serviceIconClasses }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 3v18h18M7 17v-4m5 4V9m5 8V5M6 9l5-5 4 1 5-3"/>
    </svg>
    <div class="{{ $serviceLabelClasses }}">{{ __('Travel stats') }}</div>
</a>
