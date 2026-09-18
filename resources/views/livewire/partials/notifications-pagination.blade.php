<nav aria-label="{{ __('Pagination Navigation') }}" class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
    @foreach(['previous' => $paginator->onFirstPage(), 'next' => ! $paginator->hasMorePages()] as $direction => $unavailable)
        <button type="button" wire:key="notifications-{{ $direction }}"
                @if(! $unavailable) wire:click="{{ $direction === 'previous' ? 'previousPage' : 'nextPage' }}('{{ $paginator->getPageName() }}')" @endif
                aria-disabled="{{ $unavailable ? 'true' : 'false' }}"
                class="min-h-11 rounded-lg border border-gray-500 px-4 py-2 text-sm font-medium text-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 aria-disabled:cursor-default aria-disabled:text-gray-500 dark:border-gray-400 dark:text-white dark:focus-visible:outline-blue-300 dark:aria-disabled:text-gray-400">
            {{ __('pagination.'.$direction) }}
        </button>
    @endforeach
</nav>
