@php
    $buttonId = $buttonId ?? "{$modal}-button";
    $modalId = "{$modal}-modal";
@endphp

<button
    id="{{ $buttonId }}"
    data-modal-target="{{ $modalId }}"
    data-modal-toggle="{{ $modalId }}"
    class="inline"
    type="button"
>
    <svg
        class="w-[16px] h-[16px] inline text-gray-800 dark:text-white"
        aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 20 20"
    >
        <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.6"
            d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
        />
    </svg>
</button>
