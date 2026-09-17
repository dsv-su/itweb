<div class="min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="flex min-h-20 items-center justify-between gap-3 border-b border-gray-100 bg-gray-50 px-5 py-4 dark:border-gray-700 dark:bg-gray-800">
        <h4 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{ $heading }}</h4>
        <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-700">{{ $badge }}</span>
    </div>
    <div class="p-4 sm:p-5">
        <x-chartjs-component :chart="$unitChart" />
        @if ($note)
            <p class="mt-4 text-sm leading-6 text-gray-500 dark:text-gray-400">{{ $note }}</p>
        @endif
    </div>
</div>
