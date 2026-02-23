{{--}}
<div wire:poll.visible>
    @if(count($proposals) > 0 )
        <div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                @if(count($proposals) > 0 )
                <!-- Toast -->
                <div class="mb-4 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700" role="alert" tabindex="-1" aria-labelledby="hs-toast-normal-example-label">
                    <div class="flex p-4">
                        <div class="shrink-0">
                            <svg class="shrink-0 size-4 text-blue-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <p id="hs-toast-normal-example-label" class="text-sm text-gray-700 dark:text-neutral-400">
                                Awaiting review
                            </p>
                        </div>

                    </div>
                </div>
                <!-- End Toast -->
                @endif
                <div class="mb-4 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-2 p-2">
                        @include('livewire.pp.partials.pp-list')
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
{{--}}
<div wire:poll.visible>

    <a
        href="#proposal-list"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50
               focus:rounded focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-semibold
               focus:text-gray-900 focus:shadow dark:focus:bg-gray-800 dark:focus:text-white"
    >
        Skip to proposal results
    </a>

    @php $hasProposals = count($proposals) > 0; @endphp

    @if($hasProposals)
        <main class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5" aria-labelledby="page-title">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

                <h1 id="page-title" class="sr-only">Awaiting proposals</h1>

                {{-- Screen reader update status for polling --}}
                <div class="sr-only" aria-live="polite" aria-atomic="true">
                    <span wire:loading>Updating results…</span>
                </div>

                {{-- Toast (informational) --}}
                <div
                    class="mb-4 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700"
                    role="status"
                    aria-live="polite"
                    aria-atomic="true"
                >
                    <div class="flex p-4">
                        <div class="shrink-0">
                            <svg
                                class="shrink-0 size-4 text-blue-600 mt-0.5 dark:text-blue-300"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16" height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                                aria-hidden="true"
                                focusable="false"
                            >
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                Awaiting review
                            </p>
                            <p class="mt-0.5 text-sm text-gray-700 dark:text-neutral-300">
                                Showing proposals that need your attention.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- End Toast --}}

                {{-- Results --}}
                <section
                    id="proposal-list"
                    aria-labelledby="proposal-results-title"
                    class="mb-4 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <h2 id="proposal-results-title" class="sr-only">Proposal results</h2>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-3 p-2">
                        @include('livewire.pp.partials.pp-list')
                    </div>
                </section>

            </div>
        </main>
    @else
        {{-- Accessible empty state  --}}
        <div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4"
                    role="status"
                    aria-live="polite"
                    aria-atomic="true"
                >
                    <p class="text-sm text-gray-900 dark:text-white font-semibold">No proposals awaiting review.</p>
                </div>
            </div>
        </div>
    @endif
</div>
