<div class="relative ml-4 mr-1 mt-0 md:order-0">
    <div
        x-data="{
            open: false,
            activeIndex: -1,
            items() {
                return Array.from(this.$refs.results?.querySelectorAll('[role=option]') ?? []);
            },
            setActive(i) {
                const els = this.items();
                if (!els.length) return;

                this.activeIndex = Math.max(0, Math.min(i, els.length - 1));

                const el = els[this.activeIndex];
                el?.focus?.({ preventScroll: true });
                el?.scrollIntoView?.({ block: 'nearest' });
            },
            onKeydown(e) {
                if (!this.open) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.setActive(this.activeIndex + 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.setActive(this.activeIndex - 1);
                } else if (e.key === 'Enter') {
                    const el = this.items()[this.activeIndex];
                    if (el) {
                        e.preventDefault();
                        el.click();
                    }
                } else if (e.key === 'Escape') {
                    this.open = false;
                    this.activeIndex = -1;
                    this.$refs.search?.blur();
                }
            }
        }"
        @mouseenter="open = true"
        @mouseleave="open = false"
        class="relative h-10 md:h-12"
    >
        <div
            class="group absolute right-0 top-0 h-10 md:h-12 w-12 overflow-hidden rounded-full border-2 border-transparent bg-white
                   transition-all duration-300 ease-out
                   hover:w-[240px] focus-within:w-[240px] md:hover:w-[300px] md:focus-within:w-[300px]
                   hover:border-blue-500 focus-within:border-blue-500
                   dark:bg-gray-900 dark:hover:border-blue-400 dark:focus-within:border-blue-400"
            :class="open ? 'w-[300px]' : 'w-8'"
        >
            <input
                wire:model.live="q"
                id="search"
                name="search"
                type="search"
                autocomplete="off"
                placeholder="{{ __('Search') }}"
                aria-label="{{ __('Search') }}"
                @focus="open = true"
                @blur="open = false"
                class="absolute inset-y-0 left-0 h-full rounded-full border-0 bg-transparent px-5 pr-12 text-black outline-none
                       transition-all duration-300 ease-out
                       opacity-0 pointer-events-none w-0
                       group-hover:opacity-100 group-hover:pointer-events-auto group-hover:w-full
                       focus:opacity-100 focus:pointer-events-auto focus:w-full
                       dark:text-gray-200 dark:placeholder:text-gray-400"
            >

            <button
                type="button"
                class="absolute right-0 top-0 grid h-8 md:h-10 w-8 md:w-10 place-items-center rounded-full transition-colors duration-300 ease-out
                       text-gray-900 group-hover:bg-suprimary group-hover:text-white
                       dark:text-gray-200 dark:group-hover:bg-white dark:group-hover:text-gray-900"
                @click="$nextTick(() => $el.closest('.group').querySelector('input')?.focus())"
                aria-label="{{ __('Search') }}"
            >
                <svg class="md:mt-1 h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
            </button>
        </div>

        @if ($q)
            @php
                $collectionStyles = [
                    'guidelines' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                    'software'       => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                    'it'        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                    'support'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                ];
            @endphp

            <div class="absolute right-0 top-full z-20 mt-2 w-72 md:w-96">
                <div
                    class="origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5
                           dark:bg-gray-800 dark:text-white"
                    role="listbox"
                    aria-label="{{ __('Search results') }}"
                >
                    <div class="py-1 text-sm text-gray-700 dark:text-white">
                        @forelse ($results as $result)
                            @php
                                $data = is_array($result) ? $result : (method_exists($result, 'toArray') ? $result->toArray() : (array) $result);

                                $collection = (string) ($data['collection'] ?? 'guidelines');
                                $badgeClass = $collectionStyles[$collection] ?? $collectionStyles['guidelines'];

                                $url = $data['url'] ?? null;
                                $title = $data['title'] ?? null;
                                $textField = $data['text_field'] ?? null;

                                if (is_object($url) && method_exists($url, 'value')) { $url = $url->value(); }
                                if (is_object($title) && method_exists($title, 'value')) { $title = $title->value(); }
                                if (is_object($textField) && method_exists($textField, 'value')) { $textField = $textField->value(); }
                            @endphp

                            @if ($url)
                                <a
                                    href="{{ $url }}"
                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                                    role="option"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-medium">{{ $title }}</span>

                                        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-normal {{ $badgeClass }}">
                                            {{ $collection }}
                                        </span>
                                    </div>

                                    @if (!empty($textField))
                                        <div class="mt-2 text-xs text-blue-600 dark:text-gray-300">
                                            {!! $textField !!}
                                        </div>
                                    @endif

                                    <hr class="mt-2 border-gray-200 dark:border-gray-700">
                                </a>
                            @endif
                        @empty
                            <div class="block px-4 py-2">
                                {{ __('No results found') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
