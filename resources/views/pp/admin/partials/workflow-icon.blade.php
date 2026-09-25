<span class="inline-flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-white dark:bg-gray-800 {{ $workflowRunning ? 'text-green-700 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}" title="{{ $workflowLabel }}">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
        <circle cx="12" cy="12" r="9" />
        @if($workflowRunning)
            <path d="m10 8 6 4-6 4Z" fill="currentColor" stroke="none" />
        @else
            <rect x="9" y="9" width="6" height="6" rx="1" fill="currentColor" stroke="none" />
        @endif
    </svg>
    <span class="sr-only">{{ $workflowLabel }}</span>
</span>
