<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('Notifications update when you refresh or change filters.') }}</p>
        <button type="button" wire:click="refreshNotifications" wire:loading.attr="aria-busy" class="min-h-11 rounded-lg border border-gray-500 bg-white px-4 py-2 text-sm font-semibold text-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:border-gray-400 dark:bg-gray-800 dark:text-white dark:focus-visible:outline-blue-300">{{ __('Refresh notifications') }}</button>
    </div>
    <p role="status" aria-atomic="true" class="sr-only">{{ $feedback }}</p>
    <div class="grid grid-cols-1 gap-3 min-[360px]:grid-cols-2 {{ $isHead ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }}" role="group" aria-label="{{ __('Notification categories') }}">
        @foreach(array_merge(['all' => __('All notifications'), 'review' => __('Awaiting your review'), 'returned' => __('Returned or rejected'), 'mine' => __('My requests')], $isHead ? ['approved' => __('Approved by you')] : []) as $key => $label)
            <button type="button" wire:click="$set('category', '{{ $key }}')" aria-pressed="{{ $category === $key ? 'true' : 'false' }}"
                    class="rounded-xl border p-4 text-left transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-700 dark:focus-visible:ring-blue-300 {{ $category === $key ? 'border-blue-600 bg-blue-50 text-blue-900 dark:border-blue-400 dark:bg-blue-950 dark:text-blue-100' : 'border-gray-200 bg-white text-gray-600 hover:border-blue-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                <span class="block text-sm font-medium">{{ $label }}</span>
                <span class="mt-2 block text-2xl font-bold">{{ $counts[$key] }}</span>
            </button>
        @endforeach
    </div>

    <div class="my-6 grid gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <label for="notification-search" class="mb-2 block text-sm font-medium">{{ __('Search notifications') }}</label>
            <input id="notification-search" type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by title, ID or requester') }}"
                   class="min-h-11 w-full rounded-lg border-gray-500 bg-gray-50 text-sm text-gray-950 placeholder:text-gray-600 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 dark:border-gray-400 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-300 dark:focus:ring-blue-300">
        </div>
        <div>
            <label for="notification-type" class="mb-2 block text-sm font-medium">{{ __('Request type') }}</label>
            <select id="notification-type" wire:model.live="type" class="min-h-11 w-full rounded-lg border-gray-500 bg-gray-50 text-sm text-gray-950 placeholder:text-gray-600 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 dark:border-gray-400 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-300 dark:focus:ring-blue-300">
                <option value="">{{ __('All types') }}</option>
                <option value="travelrequest">{{ __('Travelrequest') }}</option>
                <option value="projectproposal">{{ __('Projectproposal') }}</option>
            </select>
        </div>
        <div>
            <label for="notification-state" class="mb-2 block text-sm font-medium">{{ __('State') }}</label>
            <select id="notification-state" wire:model.live="state" class="min-h-11 w-full rounded-lg border-gray-500 bg-gray-50 text-sm text-gray-950 placeholder:text-gray-600 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 dark:border-gray-400 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-300 dark:focus:ring-blue-300">
                <option value="">{{ __('All states') }}</option>
                @foreach($states as $value)
                    <option value="{{ $value }}">{{ $value === 'manager_approved' ? __('Projectleader/Supervisor Approved') : __(ucwords(str_replace('_', ' ', $value))) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-600 dark:text-gray-400" role="status" aria-atomic="true">
        <span>{{ trans_choice(':count notification|:count notifications', $notifications->total(), ['count' => $notifications->total()]) }} · {{ __('Page :page of :pages', ['page' => $notifications->currentPage(), 'pages' => $notifications->lastPage()]) }}</span>
        <span>{{ __('Recently updated first') }}</span>
    </div>
    <div class="space-y-4" wire:loading.attr="aria-busy">
        @forelse($notifications as $notification)
            @php
                $currentState = (string) $notification->state;
                $isTravel = $notification->type === 'travelrequest';
                $isReview = $reviewIds->contains($notification->id);
                $isMine = (string) $notification->user_id === (string) auth()->id();
                $isUnread = $notification->status === 'unread';
                $stateClass = match (true) {
                    str_ends_with($currentState, 'denied') => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950 dark:text-red-200 dark:border-red-800',
                    str_ends_with($currentState, 'returned') => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950 dark:text-amber-200 dark:border-amber-800',
                    $currentState === 'final_approved' || $currentState === 'granted' || ($isTravel && $currentState === 'fo_approved') => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-950 dark:text-green-200 dark:border-green-800',
                    default => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950 dark:text-blue-200 dark:border-blue-800',
                };
                $url = $isTravel
                    ? route($isReview ? 'travel-request-review' : 'travel-request-show', [$notification->id, 'from' => 'notifications'])
                    : route($isReview ? 'pp.review.show' : 'pp.review.view', $notification->request_id);
                $created = $notification->created ? \Carbon\Carbon::createFromTimestamp($notification->created, config('app.timezone')) : $notification->created_at;
            @endphp
            <article wire:key="notification-{{ $notification->id }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col justify-between gap-4 sm:flex-row">
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <span>{{ $isTravel ? __('Travelrequest') : __('Projectproposal') }} · #{{ $notification->id }}</span>
                            @if($isReview)<span class="font-semibold text-blue-700 dark:text-blue-300">{{ __('Awaiting your review') }}</span>@endif
                            @if($isMine && $isUnread)<span class="font-semibold text-blue-700 dark:text-blue-300">{{ __('Unread') }}</span>@endif
                        </div>
                        <h2 class="break-words text-lg font-semibold text-gray-950 dark:text-white"><a href="{{ $url }}" class="inline-block min-h-6 underline decoration-transparent hover:decoration-current focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:focus-visible:outline-blue-300">{{ $notification->name }}</a></h2>
                        <p class="mt-1 break-words text-sm text-gray-600 dark:text-gray-400">{{ __('Requester') }}: {{ $notification->user?->name ?? __('Unknown user') }}</p>
                    </div>
                    <span class="inline-flex self-start rounded-md border px-3 py-1.5 text-xs font-semibold {{ $stateClass }}">{{ $currentState === 'manager_approved' ? __('Projectleader/Supervisor Approved') : __(ucwords(str_replace('_', ' ', $currentState))) }}</span>
                </div>
                <dl class="mt-5 grid grid-cols-1 gap-4 border-t min-[360px]:grid-cols-2 border-gray-100 pt-4 text-sm dark:border-gray-700 lg:grid-cols-4">
                    <div><dt class="text-gray-600 dark:text-gray-400">{{ __('Created') }}</dt><dd class="mt-1 break-words font-medium">{{ $created?->format('Y-m-d') ?? '—' }}</dd></div>
                    <div><dt class="text-gray-600 dark:text-gray-400">{{ __('Last updated') }}</dt><dd class="mt-1 break-words font-medium">{{ $notification->updated_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
                    @if($isTravel)
                        <div><dt class="text-gray-600 dark:text-gray-400">{{ __('Country') }}</dt><dd class="mt-1 break-words font-medium">{{ $notification->travel?->country ?: '—' }}</dd></div>
                        <div><dt class="text-gray-600 dark:text-gray-400">{{ __('Total') }}</dt><dd class="mt-1 break-words font-medium">{{ $notification->travel?->total !== null ? number_format((float) $notification->travel->total, 0, '.', ' ').' SEK' : '—' }}</dd></div>
                    @else
                        <div><dt class="text-gray-600 dark:text-gray-400">{{ __('Request ID') }}</dt><dd class="mt-1 break-words font-medium">{{ $notification->request_id }}</dd></div>
                    @endif
                </dl>
                <div class="mt-5 flex flex-wrap items-center justify-end gap-3">
                    @if($isMine)
                        <button type="button" wire:click="markRead({{ $notification->id }})" wire:loading.attr="aria-busy" aria-disabled="{{ $isUnread ? 'false' : 'true' }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 focus-visible:ring-2 focus-visible:ring-blue-700 dark:focus-visible:ring-blue-300 dark:text-gray-300 dark:hover:bg-gray-700">{{ $isUnread ? __('Mark as read') : __('Read') }}<span class="sr-only">: {{ $notification->name }} (#{{ $notification->id }})</span></button>
                    @endif
                    <a href="{{ $url }}" class="inline-flex min-h-11 items-center rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:focus-visible:outline-blue-300">{{ $isReview ? __('Review request') : __('View request') }}<span class="sr-only">: {{ $notification->name }} (#{{ $notification->id }})</span></a>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center dark:border-gray-600 dark:bg-gray-800">
                <h2 class="text-lg font-semibold">{{ __('No notifications found') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('There are no requests in this view. Try another category or adjust your filters.') }}</p>
            </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
        <div class="mt-6">{{ $notifications->onEachSide(1)->links('livewire.partials.notifications-pagination') }}</div>
    @endif
</div>
