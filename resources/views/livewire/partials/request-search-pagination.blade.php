@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';

    $buttonClass = 'inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 bg-white px-3 text-xs font-medium text-gray-600 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-blue-500/60 dark:hover:bg-blue-500/10 dark:hover:text-blue-300 dark:focus:ring-offset-gray-900';
    $activeClass = 'inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-blue-600 bg-blue-600 px-3 text-xs font-semibold text-white shadow-sm dark:border-blue-500 dark:bg-blue-500';
    $disabledClass = 'inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 bg-gray-50 px-3 text-xs font-medium text-gray-400 dark:border-gray-700 dark:bg-gray-800/70 dark:text-gray-500';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ __('Showing') }}
            <span class="text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
            {{ __('to') }}
            <span class="text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
            {{ __('of') }}
            <span class="text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
            {{ __('results') }}
        </p>

        <div class="flex items-center gap-2 overflow-x-auto">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="{{ $disabledClass }}">
                    <span aria-hidden="true">&lsaquo;</span>
                </span>
            @else
                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="{{ $buttonClass }}" aria-label="{{ __('pagination.previous') }}">
                    <span aria-hidden="true">&lsaquo;</span>
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="{{ $disabledClass }}">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $activeClass }}">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button" wire:key="request-search-page-{{ $page }}" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="{{ $buttonClass }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="{{ $buttonClass }}" aria-label="{{ __('pagination.next') }}">
                    <span aria-hidden="true">&rsaquo;</span>
                </button>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="{{ $disabledClass }}">
                    <span aria-hidden="true">&rsaquo;</span>
                </span>
            @endif
        </div>
    </nav>
@endif
