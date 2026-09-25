@if($proposal->allowComplete() && $proposal->dashboard->status != 'resumed')
    <a
        href="{{ route('pp.complete', $proposal->id) }}#proposal-attachments"
        class="inline-flex min-h-8 items-center justify-center gap-2 rounded-lg border border-blue-800 bg-blue-800 px-2.5 py-1 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 dark:border-blue-400 dark:bg-blue-400 dark:text-gray-950 dark:hover:bg-blue-300 dark:focus-visible:ring-offset-gray-800">
        Complete
    </a>
@endif
