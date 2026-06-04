@php
    $defaultMessage = $defaultMessage ?? __('This is a required input');
    $scrollToForm = $scrollToForm ?? false;
@endphp

@error($field)
    <p
        class="mt-3 text-sm leading-6 text-red-600"
        @if($scrollToForm) x-init="$el.closest('form').scrollIntoView()" @endif
    >
        {{ $defaultMessage }}
    </p>
@enderror
