<div wire:poll.visible.10s> <!-- Polls every 10s only when visible -->
    @foreach($user_requests as $user_request)
        @php
            $isTravelRequest = $user_request->type === 'travelrequest';
            $route = $isTravelRequest ? route('travel-request-show', $user_request->id) : route('pp.show', 'my');

            // Map status colors and text
            $statusClassMap = [
                'manager_denied' => 'red',
                'fo_denied' => 'red',
                'head_denied' => 'red',
                'manager_returned' => 'blue',
                'fo_returned' => 'blue',
                'head_returned' => 'blue',
                'fo_approved' => 'green',
                'final_approved' => 'green',
                'pending' => 'yellow',
                'submitted' => 'yellow',
                'manager_approved' => 'yellow',
                'head_approved' => 'yellow',
                'vice_approved' => 'yellow',
            ];

            $statusTextMap = [
                'pending' => __("Pending"),
                'submitted' => __("Submitted"),
                'manager_approved' => __("Processing"),
                'manager_denied' => __("Denied"),
                'manager_returned' => __("Returned"),
                'head_approved' => __("Processing"),
                'vice_approved' => __("Processing"),
                'fo_denied' => __("Denied"),
                'fo_returned' => __("Returned"),
                'fo_approved' => __("Approved"),
                'head_denied' => __("Denied"),
                'head_returned' => __("Returned"),
                'vice_returned' => __("Returned"),
                'final_approved' => __("Approved"),
            ];
            $state = (string) $user_request->state;
            $badgeColor = $statusClassMap[$state] ?? 'yellow';
            $statusText = $statusTextMap[$state] ?? __("Sent");

            // Approval status
            $approvalStatus = match(true) {
                $user_request->status == 'unread' && $state == 'fo_approved' => ['green', __("Closed")],
                in_array($state, ['manager_returned', 'head_returned', 'fo_returned']) => ['gray', __("Pending")],
                in_array($state, ['manager_denied', 'head_denied', 'fo_denied']) => ['gray', __("Closed")],
                default => ['green', __("Sent")],
            };
        @endphp

        <a wire:key="user_request_{{ $user_request->id }}" href="{{ $route }}" wire:click="read({{ $user_request->id }})"
           class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">

            <div class="flex-shrink-0 mt-4">
                @if($isTravelRequest)
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="1" d="M8 8v1h4V8m4 7H4a1 1 0 0 1-1-1V5h14v9a1 1 0 0 1-1 1ZM2 1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3"/>
                    </svg>
                @endif
            </div>

            <div class="pl-3 w-full">
                <div class="text-gray-900 dark:text-white font-semibold text-sm mb-1.5">
                    [{{ $user_request->id }}] {{ $user_request->name }}
                </div>

                <div class="text-xs font-medium text-primary-700 dark:text-white">
                    <span class="bg-{{ $approvalStatus[0] }}-100 text-{{ $approvalStatus[0] }}-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-{{ $approvalStatus[0] }}-400 border border-{{ $approvalStatus[0] }}-400">
                        {{ $approvalStatus[1] }}
                    </span>

                    {{ Carbon\Carbon::createFromTimestamp($user_request->created)->toDateString() }} | Status:

                    <span class="bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-{{ $badgeColor }}-400 border border-{{ $badgeColor }}-400">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
        </a>
    @endforeach
</div>
