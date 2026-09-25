<div class="relative overflow-hidden rounded-2xl border border-susecondary bg-white dark:border-susecondary dark:bg-gray-800">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-5">

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
                            class="bg-gray-50 border border-susecondary text-gray-900 text-sm rounded-lg
                     block w-full pl-10 p-2
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
                     dark:bg-gray-700 dark:border-susecondary dark:text-white dark:placeholder-gray-300
                     dark:focus-visible:ring-blue-400 dark:focus-visible:ring-offset-gray-800"
                            placeholder="Search proposals"
                        />
                    </div>

                    <!-- Optional: Live region for “X results”  -->
                    <div id="proposal-search-status" class="sr-only" aria-live="polite" aria-atomic="true">

                    </div>

                    @php
                        $proposalStateFilters = [
                            '' => ['label' => 'All'],
                            'awaiting' => ['label' => 'Awaiting'],
                            'processing' => ['label' => 'Processing'],
                            'returned' => ['label' => 'Returned'],
                            'approved' => ['label' => 'Approved'],
                            'granted' => ['label' => 'Granted'],
                            'denied' => ['label' => 'Rejected'],
                        ];

                    @endphp

                    <fieldset class="mt-5 border-t border-susecondary pt-4 dark:border-susecondary">
                        <legend class="sr-only">
                            Proposal state
                        </legend>

                        <div class="flex w-full flex-wrap justify-center gap-2" role="group" aria-label="Filter proposals by state">
                            @foreach($proposalStateFilters as $filterValue => $filter)
                                @php
                                    $isActive = $proposalStateFilter === $filterValue;
                                    $stateClasses = $isActive
                                        ? 'border-blue-800 bg-blue-800 text-white dark:border-blue-400 dark:bg-blue-400 dark:text-gray-950'
                                        : 'border-susecondary bg-white text-gray-600 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-800 dark:border-susecondary dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                                @endphp

                                <button
                                    type="button"
                                    wire:click="$set('proposalStateFilter', '{{ $filterValue }}')"
                                    aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                                    class="{{ $stateClasses }} inline-flex min-h-10 min-w-24 items-center justify-center rounded-lg border px-3.5 py-2 text-xs font-semibold shadow-sm transition
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
                                           dark:focus-visible:ring-blue-400 dark:focus-visible:ring-offset-gray-800">
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
