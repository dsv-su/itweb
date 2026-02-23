<h3 class="mb-4 text-blue-600 font-semibold dark:font-medium dark:text-white">Budget Template file!</h3>

<div class="mb-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">

    @foreach(($template->files ?? []) as $key => $pp_file)
        @php
            // Livewire-safe key: no dots, spaces, etc.
            $safeKey = 'f_' . md5((string) $key);
        @endphp

        <div class="p-4 md:p-5 space-y-7" wire:key="file-row-{{ $safeKey }}">
            <div class="mb-2 flex justify-between items-center">
                <div class="flex items-center gap-x-3">
                <span class="inline-block px-2 py-1 text-xs
                    {{ in_array($pp_file['type'] ?? '', ['eng','swe','eu']) ? 'text-green-600 border-green-600' : 'text-gray-500 border-gray-200' }}
                    border rounded-lg text-center dark:border-neutral-700 dark:text-neutral-500">

                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg>

                    {{ strtoupper($pp_file['type'] ?? '') }}
                </span>

                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $key }}</p>
                        <p class="text-xs text-gray-500 dark:text-neutral-500">
                            {{ $pp_file['size'] ?? '?' }} KB |
                            Date: {{ $pp_file['date'] ?? '?' }} |
                            Uploaded by: {{ $pp_file['uploader'] ?? '?' }} |

                            <span class="bg-blue-100 text-blue-800 text-[10px] font-medium me-1 px-1 py-0 rounded
                                     dark:bg-gray-700 dark:text-blue-400 border border-blue-400 leading-none">
                            {{ strtoupper($pp_file['review'] ?? '') }}
                        </span>
                        </p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-x-2">
                    @if($allow)
                        <button
                            wire:click.prevent="removefile(@js($key))"
                            type="button"
                            class="relative text-red-600 hover:text-red-800 focus:outline-none focus:text-red-800
                               disabled:opacity-50 disabled:pointer-events-none
                               dark:text-red-400 dark:hover:text-red-500 dark:focus:text-red-500">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                <line x1="14" x2="14" y1="11" y2="17"></line>
                            </svg>
                            <span class="sr-only">Delete</span>
                        </button>

                        <select
                            {{-- Livewire-safe binding key --}}
                            wire:model.live="templateLang.{{ $safeKey }}"
                            {{-- keep your file type in sync initially --}}
                            @selected(false)
                            class="bg-yellow-50 text-yellow-700 border border-yellow-400 text-[0.65rem] font-medium
                               me-1 px-1 py-1 rounded hover:text-yellow-800
                               dark:bg-yellow-800 dark:text-yellow-300 appearance-none leading-none">
                            <option value="eng">English</option>
                            <option value="swe">Swedish</option>
                            <option value="eu">EU</option>
                        </select>

                        {{-- Ensure templateLang has an initial value if not set --}}
                        @php
                            // This is only visual; real init should be in component mount/storefiles.
                            // But it prevents "blank select" if templateLang isn't populated yet.
                        @endphp
                    @endif

                    <button
                        wire:click.prevent="downloadfile(@js($key))"
                        type="button"
                        class="relative text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:text-blue-400 dark:hover:text-blue-500 dark:focus:text-blue-500 px-4 py-2 text-base">
                        <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 13V4"></path>
                            <path d="M8 14l4 4 4-4"></path>
                            <path d="M5 20h14"></path>
                        </svg>
                        <span class="sr-only">Download All</span>
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <div class="bg-gray-50 border-t border-gray-200 rounded-b-xl py-2 px-4 md:px-5 dark:bg-white/10 dark:border-neutral-700">
        <div class="flex flex-wrap justify-between items-center gap-x-3">
            <div>
                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ count($template->files ?? []) }} files
                </span>
            </div>
        </div>
    </div>
</div>
