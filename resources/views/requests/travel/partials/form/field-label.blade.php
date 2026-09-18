@php
    $required = $required ?? false;
    $modal = $modal ?? null;
    $class = trim('block mb-2 text-sm font-medium text-gray-900 dark:text-white ' . ($class ?? ''));
@endphp

<div class="{{ $class }}">
    <label for="{{ $for }}">
        {{ $label }}

        @if($required)
            <span aria-hidden="true" class="text-red-600"> *</span><span class="sr-only">{{ __('(required)') }}</span>
        @endif
    </label>

    @if($modal)
        @include('requests.travel.partials.form.help-button', ['modal' => $modal])
    @endif
</div>
