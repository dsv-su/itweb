<div id="searchinput" class="relative grow max-w-96 ml-4 mr-2 mt-2 md:order-0 md:mt-0">
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="h-5 w-5 text-blue-800 dark:text-gray-200" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </div>

    <input
        wire:model.live="q"
        id="search"
        name="search"
        type="search"
        autocomplete="off"
        aria-label="{{ __('Search') }}"
        aria-expanded="{{ $q ? 'true' : 'false' }}"
        class="w-full rounded-xl border border-susecondary bg-white py-2 pl-10 pr-4 text-black placeholder:text-blue-800
               focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40
               sm:text-sm dark:bg-gray-900 dark:text-gray-200 dark:placeholder:text-gray-200"
        placeholder="{{ __('Search') }}"
    >

    @if ($q)
        @php
            $collectionStyles = [
                'education' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                'phd'       => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                'it'        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                'default'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            ];
        @endphp

        <div
            class="absolute z-20 mt-2 w-72 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5
                   md:right-0 md:w-96 dark:bg-gray-800 dark:text-white"
            role="listbox"
            aria-label="{{ __('Search results') }}"
        >
            <div class="py-1 text-sm text-gray-700 dark:text-white">
                @forelse ($results as $result)
                    @php
                        // Normalize results so we can safely read keys regardless of whether this is an array
                        // or a Statamic object/Value wrapper.
                        $data = is_array($result) ? $result : (method_exists($result, 'toArray') ? $result->toArray() : (array) $result);

                        $collection = (string) ($data['collection'] ?? 'default');
                        $badgeClass = $collectionStyles[$collection] ?? $collectionStyles['default'];

                        $url = $data['url'] ?? null;
                        $title = $data['title'] ?? null;
                        $textField = $data['text_field'] ?? null;

                        if (is_object($url) && method_exists($url, 'value')) { $url = $url->value(); }
                        if (is_object($title) && method_exists($title, 'value')) { $title = $title->value(); }
                        if (is_object($textField) && method_exists($textField, 'value')) { $textField = $textField->value(); }
                    @endphp

                    <a
                        href="{{ $result['url'] }}"
                        class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                        role="option"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium">{{ $result['title'] }}</span>

                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-normal {{ $badgeClass }}">
                                {{ $collection }}
                            </span>
                        </div>

                        @if (!empty($result['text_field']))
                            <div class="mt-2 text-xs text-blue-600 dark:text-gray-300">
                                {!! $result['text_field'] !!}
                            </div>
                        @endif

                        <hr class="mt-2 border-gray-200 dark:border-gray-700">
                    </a>
                @empty
                    <div class="block px-4 py-2">
                        {{ __('No results found') }}
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
