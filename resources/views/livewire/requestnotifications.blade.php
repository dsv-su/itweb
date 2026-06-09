<div wire:poll.keep-alive>
    @foreach($requests as $request)
        @php
            $isTravelRequest = $request->type === 'travelrequest';
            $route = $isTravelRequest
                ? route('travel-request-review', $request->id)
                : route('pp.show', 'awaiting');

            $state = (string) $request->state;
            $isUnread = $request->status === 'unread';

            $stateLabel = match ($state) {
                'pending' => __('Pending'),
                'submitted' => __('Submitted'),
                'manager_approved' => __('Approved by manager'),
                'fo_approved' => __('Approved by FO'),
                'head_approved' => __('Approved by Unit head'),
                'vice_approved' => __('Approved by Vice head'),
                'complete' => __('Review'),
                'manager_denied', 'fo_denied', 'head_denied' => __('Denied'),
                'vice_denied' => __('Denied by Vice head'),
                'vice_returned' => __('Returned by Vice head'),
                default => __(ucwords(str_replace('_', ' ', $state))),
            };

            $sentBadgeClass = $isUnread
                ? 'bg-green-100 text-green-800 dark:text-green-400 border-green-400'
                : 'bg-gray-100 text-gray-800 dark:text-gray-400 border-gray-500';

            $stateBadgeClass = in_array($state, ['manager_denied', 'fo_denied', 'head_denied', 'vice_denied'], true)
                ? 'bg-red-100 text-red-800 dark:text-red-400 border-red-400'
                : 'bg-yellow-100 text-yellow-800 dark:text-yellow-300 border-yellow-300';
        @endphp

        <a wire:key="request_notification_{{ $request->id }}"
           href="{{ $route }}"
           class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
            <div class="flex-shrink-0 mt-4">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 4a4 4 0 0 1 4 4v6M5 4a4 4 0 0 0-4 4v6h8M5 4h9M9 14h10V8a3.999 3.999 0 0 0-2.066-3.5M9 14v5m0-5h4v5m-9-8h2m8-4V1h2"/>
                </svg>
            </div>
            <div class="pl-3 w-full">
                <div class="text-gray-900 dark:text-white font-semibold text-sm mb-1.5">[{{ $request->id }}] {{ $request->name }}</div>
                <div class="text-xs font-medium text-primary-700 dark:text-white">
                    <span class="{{ $sentBadgeClass }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 border">
                        {{ __('Sent') }}
                    </span>
                    {{ Carbon\Carbon::createFromTimestamp($request->created)->toDateString() }}
                    | {{ __('Status') }}:
                    <span class="{{ $stateBadgeClass }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 border">
                        {{ $stateLabel }}
                    </span>
                </div>
            </div>
        </a>
    @endforeach
</div>
