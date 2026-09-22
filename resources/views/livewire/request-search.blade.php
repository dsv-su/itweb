<div class="flex min-w-0 flex-col flex-1 w-full">
    @php
        $canEditCompleted = auth()->user()?->isFO() ?? false;
    @endphp
    <label for="request-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">{{ __('Search') }}</label>
    <div class="relative mb-6 w-full max-w-3xl mx-auto">
        <input type="search" id="request-search" wire:model.live="searchTerm"
               class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="{{__("Please refine your search by typing to filter ID, User, Country, Purpose, or ProjectID")}}">
    </div>
    <div class="mb-6 flex justify-center">
        <div class="grid w-full grid-cols-2 sm:inline-flex sm:w-auto rounded-lg border border-gray-200 bg-white p-1 shadow-sm dark:border-gray-700 dark:bg-gray-800" role="group" aria-label="{{ __('Request type filter') }}">
            <button
                type="button"
                wire:click="$set('requestType', 'travelrequest')"
                wire:loading.attr="disabled"
                class="rounded-md px-4 py-2 text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $requestType === 'travelrequest' ? 'bg-blue-600 text-white shadow-sm dark:bg-blue-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}"
            >
                {{ __('Travelrequest') }}
            </button>
            <button
                type="button"
                wire:click="$set('requestType', 'projectproposal')"
                wire:loading.attr="disabled"
                class="rounded-md px-4 py-2 text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $requestType === 'projectproposal' ? 'bg-blue-600 text-white shadow-sm dark:bg-blue-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}"
            >
                {{ __('Projectproposal') }}
            </button>
        </div>
    </div>
    @if($foStatus)
        <div role="status" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
            {{ $foStatus }}
        </div>
    @endif

    @if($awaitingFoReview !== null)
        <section class="mb-8 w-full min-w-0 rounded-xl border border-yellow-200 bg-yellow-50 dark:border-yellow-700 dark:bg-yellow-900/20" aria-labelledby="awaiting-fo-review-heading">
            <h2 id="awaiting-fo-review-heading" class="flex flex-wrap items-center gap-2 px-4 py-4 text-lg font-semibold text-yellow-900 dark:text-yellow-200">
                <span>{{ __('Awaiting FO review') }}</span>
                <span class="inline-flex min-w-7 items-center justify-center rounded-full bg-yellow-200 px-2 py-0.5 text-sm dark:bg-yellow-800">{{ $awaitingFoReview->total() }}</span>
            </h2>
            @if($awaitingFoReview->hasPages())
                <div class="px-4 pb-4">
                    {{ $awaitingFoReview->onEachSide(1)->links('livewire.partials.request-search-pagination') }}
                </div>
            @endif
            @if($awaitingFoReview->isNotEmpty())
                <div class="min-w-0 border-t border-yellow-200 p-3 xl:p-0 dark:border-yellow-700">
                    @include('livewire.partials.request-search-table', ['dashboards' => $awaitingFoReview])
                </div>
            @else
                <p class="px-4 pb-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No requests awaiting FO review match your search.') }}</p>
            @endif
        </section>
    @endif

    <section class="w-full min-w-0" aria-label="{{ __('Other requests') }}">
        @if($awaitingFoReview !== null)
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Other requests') }}</h2>
        @endif
        @if($dashboards->hasPages())
            <div class="mb-4">
                {{ $dashboards->onEachSide(1)->links('livewire.partials.request-search-pagination') }}
            </div>
        @endif
        @include('livewire.partials.request-search-table')
    </section>
</div>
