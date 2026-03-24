@php
    $state = (string) ($tr->state ?? '');

    $states = [
        'submitted' => [
            'label' => __('Submitted'),
            'tone' => 'info',
            'progress' => 33,
        ],
        'manager_approved' => [
            'label' => __('Processed by manager'),
            'tone' => 'info',
            'progress' => 33,
        ],
        'head_approved' => [
            'label' => __('Being processed by FO'),
            'tone' => 'info',
            'progress' => 90,
        ],
        'fo_approved' => [
            'label' => __('Approved'),
            'tone' => 'success',
            'progress' => 100,
        ],

        'manager_denied' => [
            'label' => __('Denied'),
            'tone' => 'danger',
            'progress' => 100,
        ],
        'head_denied' => [
            'label' => __('Denied'),
            'tone' => 'danger',
            'progress' => 100,
        ],
        'fo_denied' => [
            'label' => __('Denied'),
            'tone' => 'danger',
            'progress' => 100,
        ],

        'manager_returned' => [
            'label' => __('Returned'),
            'tone' => 'danger',
            'progress' => 100,
        ],
        'head_returned' => [
            'label' => __('Returned'),
            'tone' => 'danger',
            'progress' => 100,
        ],
        'fo_returned' => [
            'label' => __('Returned'),
            'tone' => 'danger',
            'progress' => 100,
        ],
    ];

    $meta = $states[$state] ?? null;

    $badgeClassesByTone = [
        'info' => 'bg-blue-100 text-blue-800 dark:bg-gray-700 dark:text-blue-400 border-blue-400',
        'success' => 'bg-green-100 text-green-800 dark:bg-gray-700 dark:text-green-400 border-green-400',
        'danger' => 'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border-red-400',
    ];

    $barClassesByTone = [
        'info' => 'bg-blue-600 dark:bg-blue-500',
        'success' => 'bg-blue-600 dark:bg-blue-500',
        'danger' => 'bg-red-600 dark:bg-red-500',
    ];
@endphp

@if($meta)
    <span
        class="inline-flex items-center border rounded font-medium
               px-3 py-1 text-sm sm:px-6 sm:py-2
               {{ $badgeClassesByTone[$meta['tone']] ?? $badgeClassesByTone['info'] }}"
    >
        {{ $meta['label'] }}
    </span>

    <div
        class="w-full h-3 sm:h-4 mb-4 mt-4 sm:mt-6 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden"
        role="progressbar"
        aria-valuenow="{{ (int) $meta['progress'] }}"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-label="{{ __('Request progress') }}"
    >
        <div
            class="h-full rounded-full {{ $barClassesByTone[$meta['tone']] ?? $barClassesByTone['info'] }}"
            style="width: {{ (int) $meta['progress'] }}%"
        ></div>
    </div>
@endif
