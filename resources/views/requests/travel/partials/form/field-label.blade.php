@php
    $required = $required ?? false;
    $modal = $modal ?? null;
    $class = trim('block mb-2 text-sm font-medium text-gray-900 dark:text-white ' . ($class ?? ''));
@endphp

<label for="{{ $for }}" class="{{ $class }}">
    {{ $label }}

    @if($required)
        <span class="text-red-600"> *</span>
    @endif

    @if($modal)
        @include('requests.travel.partials.form.help-button', ['modal' => $modal])
    @endif
</label>
