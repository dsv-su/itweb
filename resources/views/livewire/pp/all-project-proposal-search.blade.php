{{--}}
<div>
    <div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Search Form -->
            @include('livewire.pp.partials.search')
            <!--@include('livewire.pp.partials.filter_denied_proposals')-->
            <!-- Proposal List -->
            <div class="mb-4 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-2 p-2">
                    @include('livewire.pp.partials.pp-list')
                </div>
            </div>

            <!-- Pagination -->
            @if ($proposals->hasPages())
                {{ $proposals->links() }}
            @endif
        </div>
    </div>
</div>
{{--}}
<div>

    <a
        href="#proposal-list"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50
               focus:rounded focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-semibold
               focus:text-gray-900 focus:shadow dark:focus:bg-gray-800 dark:focus:text-white"
    >
        Skip to proposal results
    </a>

    <main class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5" aria-labelledby="page-title">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

            {{-- Page title for landmark labeling --}}
            <h1 id="page-title" class="sr-only">Proposals</h1>

            {{-- Search --}}
            <section aria-labelledby="proposal-search-title" class="mb-4">
                <h2 id="proposal-search-title" class="sr-only">Search proposals</h2>
                @include('livewire.pp.partials.search')
                {{-- @include('livewire.pp.partials.filter_denied_proposals') --}}
            </section>

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

            {{-- Pagination --}}
            @if ($proposals->hasPages())
                <nav aria-label="Proposal pages" class="mt-4">
                    {{ $proposals->links() }}
                </nav>
            @endif

        </div>
    </main>
</div>
