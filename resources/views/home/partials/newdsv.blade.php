<div class="relative flex flex-col items-start justify-start rounded-xl border border-susecondary p-6 text-center md:order-1 dark:border-gray-800">
    <h3 class="text-lg font-semibold text-gray-800 md:text-xl dark:text-gray-200">
        {{__("New at DSV")}}
    </h3>

    <p class="mt-2 w-full text-left text-gray-500 dark:text-gray-300">
        {{__("Here is a collection of links to help you get started with the basics.")}}
    </p>

    <div class="mt-3 w-full">
        @php
        use Statamic\Facades\Entry;
        use Statamic\Facades\Site;

        $entry = Entry::query()
            ->where('collection', 'it')
            ->where('slug', 'new-at-dsv')
            ->first();

        $site = Site::current()->handle();

        $localized = $entry ? $entry->in($site) : null;
        @endphp

        @if ($localized)
            <a href="{{ $localized->url() }}"
               aria-label="{{ __('New at DSV') }}"
               class="inline-flex w-full items-center justify-center gap-x-1.5 rounded-lg border border-susecondary px-4 py-2 text-center font-medium text-blue-800 dark:text-white"
            >
                {{__("New at DSV")}}
                <svg class="w-2.5 h-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                </svg>
            </a>
        @endif
    </div>

</div>
