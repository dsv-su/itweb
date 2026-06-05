<div class="mb-4 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

        <div class="w-full">
            <form class="flex items-center gap-2 sm:gap-3" role="search" aria-label="Search proposals">
                <div class="w-full">
                    <!-- label for WCAG usability -->
                    <label for="proposal-search" class="block text-sm font-semibold text-gray-900 dark:text-white">
                        Search
                    </label>

                    <p id="proposal-search-hint" class="mt-1 text-sm text-gray-600 dark:text-neutral-300">
                        Type to filter the list of proposals.
                    </p>

                    <div class="relative mt-2 w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <path fill-rule="evenodd"
                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>

                        <input
                            wire:model.live="searchProposal"
                            type="search"
                            id="proposal-search"
                            name="q"
                            autocomplete="off"
                            inputmode="search"
                            aria-describedby="proposal-search-hint proposal-search-status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                     block w-full pl-10 p-2
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2
                     dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-300
                     dark:focus-visible:ring-primary-400 dark:focus-visible:ring-offset-gray-800"
                            placeholder="Search proposals"
                        />
                    </div>

                    <!-- Optional: Live region for “X results”  -->
                    <div id="proposal-search-status" class="sr-only" aria-live="polite" aria-atomic="true">

                    </div>

                    @php
                        $proposalStateFilters = [
                            '' => ['label' => 'All', 'accent' => 'slate'],
                            'awaiting' => ['label' => 'Awaiting', 'accent' => 'amber'],
                            'processing' => ['label' => 'Processing', 'accent' => 'sky'],
                            'returned' => ['label' => 'Returned', 'accent' => 'orange'],
                            'approved' => ['label' => 'Approved', 'accent' => 'emerald'],
                            'granted' => ['label' => 'Granted', 'accent' => 'violet'],
                            'denied' => ['label' => 'Rejected', 'accent' => 'rose'],
                        ];

                        $proposalStateFilterStyles = [
                            'slate' => [
                                'active' => 'border-slate-800 bg-slate-800 text-white shadow-slate-200 dark:border-slate-300 dark:bg-slate-200 dark:text-slate-950 dark:shadow-none',
                                'inactive' => 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-600 dark:bg-gray-800 dark:text-slate-200 dark:hover:bg-gray-700',
                            ],
                            'amber' => [
                                'active' => 'border-amber-700 bg-amber-700 text-white shadow-amber-100 dark:border-amber-300 dark:bg-amber-300 dark:text-amber-950 dark:shadow-none',
                                'inactive' => 'border-amber-200 bg-amber-50 text-amber-800 hover:border-amber-300 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-950/30 dark:text-amber-200 dark:hover:bg-amber-900/50',
                            ],
                            'sky' => [
                                'active' => 'border-sky-700 bg-sky-700 text-white shadow-sky-100 dark:border-sky-300 dark:bg-sky-300 dark:text-sky-950 dark:shadow-none',
                                'inactive' => 'border-sky-200 bg-sky-50 text-sky-800 hover:border-sky-300 hover:bg-sky-100 dark:border-sky-700 dark:bg-sky-950/30 dark:text-sky-200 dark:hover:bg-sky-900/50',
                            ],
                            'orange' => [
                                'active' => 'border-orange-700 bg-orange-700 text-white shadow-orange-100 dark:border-orange-300 dark:bg-orange-300 dark:text-orange-950 dark:shadow-none',
                                'inactive' => 'border-orange-200 bg-orange-50 text-orange-800 hover:border-orange-300 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-950/30 dark:text-orange-200 dark:hover:bg-orange-900/50',
                            ],
                            'emerald' => [
                                'active' => 'border-emerald-700 bg-emerald-700 text-white shadow-emerald-100 dark:border-emerald-300 dark:bg-emerald-300 dark:text-emerald-950 dark:shadow-none',
                                'inactive' => 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:border-emerald-300 hover:bg-emerald-100 dark:border-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-200 dark:hover:bg-emerald-900/50',
                            ],
                            'violet' => [
                                'active' => 'border-violet-700 bg-violet-700 text-white shadow-violet-100 dark:border-violet-300 dark:bg-violet-300 dark:text-violet-950 dark:shadow-none',
                                'inactive' => 'border-violet-200 bg-violet-50 text-violet-800 hover:border-violet-300 hover:bg-violet-100 dark:border-violet-700 dark:bg-violet-950/30 dark:text-violet-200 dark:hover:bg-violet-900/50',
                            ],
                            'rose' => [
                                'active' => 'border-rose-700 bg-rose-700 text-white shadow-rose-100 dark:border-rose-300 dark:bg-rose-300 dark:text-rose-950 dark:shadow-none',
                                'inactive' => 'border-rose-200 bg-rose-50 text-rose-800 hover:border-rose-300 hover:bg-rose-100 dark:border-rose-700 dark:bg-rose-950/30 dark:text-rose-200 dark:hover:bg-rose-900/50',
                            ],
                        ];
                    @endphp

                    <fieldset class="mt-4 text-center">
                        <legend class="sr-only">
                            Proposal state
                        </legend>

                        <div class="mt-3 flex w-full flex-wrap justify-center gap-2" role="group" aria-label="Filter proposals by state">
                            @foreach($proposalStateFilters as $filterValue => $filter)
                                @php
                                    $isActive = $proposalStateFilter === $filterValue;
                                    $accent = $filter['accent'];
                                    $stateClasses = $proposalStateFilterStyles[$accent][$isActive ? 'active' : 'inactive'];
                                @endphp

                                <button
                                    type="button"
                                    wire:click="$set('proposalStateFilter', '{{ $filterValue }}')"
                                    aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                                    class="{{ $stateClasses }} inline-flex min-h-10 min-w-24 items-center justify-center rounded-md border px-3.5 py-2 text-xs font-semibold shadow-sm transition
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2
                                           dark:focus-visible:ring-primary-400 dark:focus-visible:ring-offset-gray-800">
                                    {{ $filter['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
</div>
