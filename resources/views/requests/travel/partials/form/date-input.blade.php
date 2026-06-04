@php
    $inputClass = 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';
@endphp

<div class="flex flex-col w-full sm:flex-1 sm:min-w-0 {{ $wrapperClass ?? '' }}">
    @include('requests.travel.partials.form.field-label', [
        'for' => $id,
        'label' => $label,
        'class' => 'dark:text-gray-200',
    ])

    <div class="relative w-full sm:w-auto">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg
                class="w-4 h-4 text-blue-700 dark:text-gray-200"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>
        </div>

        <input
            name="{{ $name }}"
            id="{{ $id }}"
            type="text"
            value="{{ $value }}"
            class="{{ $inputClass }}"
            placeholder="{{ $placeholder }}"
        >

        @include('requests.travel.partials.form.field-error', ['field' => $name])
    </div>
</div>
