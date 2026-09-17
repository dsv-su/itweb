<!-- Stepper -->

@php
    // Dashboard state mapped to a step number
    $stateToStep = [
        'submitted' => 1,
        'manager_approved' => 2,
        'manager_denied' => 1,
        'manager_returned' => 1,
        'head_approved'  => 3,
        'head_returned'  => 2,
        'head_denied'  => 2,
        'fo_approved' => 4,
        'fo_returned' => 3,
        'fo_denied' => 3,
    ];
    // Determine the current step based on the dashboard state (or 1 if not set)
    $currentStep = isset($dashboard) && isset($stateToStep[(string)$dashboard->state])
                     ? $stateToStep[(string)$dashboard->state]
                     : 1;
    $progressState = (string) ($dashboard->state ?? 'submitted');
    $reviewDetails = $tr->review_details ?? [];
    $progressSignals = ! empty($dashboard->workflow_id)
        ? \Workflow\Models\StoredWorkflowSignal::query()
            ->where('stored_workflow_id', $dashboard->workflow_id)
            ->whereIn('method', ['manager_approve', 'manager_deny', 'manager_return', 'head_approve', 'head_deny', 'head_return'])
            ->orderByDesc('id')
            ->get()
        : collect();
    $progressReviewerIds = collect([$dashboard->manager_id ?? null, $dashboard->head_id ?? null])->filter()->unique();
    $progressReviewers = $progressReviewerIds->isNotEmpty()
        ? \App\Models\User::query()->whereIn('id', $progressReviewerIds)->get()->keyBy('id')
        : collect();
    $decisionLabels = ['approve' => __('Approved'), 'deny' => __('Denied'), 'return' => __('Returned')];
    $decisionClasses = [
        'approve' => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300',
        'deny' => 'bg-red-50 text-red-800 ring-red-600/20 dark:bg-red-400/10 dark:text-red-300',
        'return' => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-300',
    ];
@endphp

<ul class="relative flex flex-col md:flex-row gap-2">
    @for($i = 1; $i <= 4; $i++)
        @php
            // For each step, if it's less than or equal to the current step, mark as "completed" (blue)
            $isCompleted = $i <= $currentStep;
            $role = [2 => 'manager', 3 => 'head', 4 => 'fo'][$i] ?? null;
            $isDenied = $role && $progressState === $role.'_denied';
            $isReturned = $role && $progressState === $role.'_returned';
            $bgColor = $isCompleted ? 'bg-blue-500' : 'bg-gray-100';
            $darkBgColor = $isCompleted ? 'dark:bg-blue-600' : 'dark:bg-neutral-700';
            $textColor = $isCompleted ? 'text-white' : 'text-gray-800';
            $describeText = $isCompleted ? 'text-blue-500' : 'text-gray-800';
            if ($isDenied || $isReturned) {
                $bgColor = $isDenied ? 'bg-red-500' : 'bg-amber-500';
                $darkBgColor = $isDenied ? 'dark:bg-red-600' : 'dark:bg-amber-600';
                $textColor = 'text-white';
            }
            $review = $reviewDetails[$role] ?? null;
            $historicalSignal = $role ? $progressSignals->first(fn ($signal) => str_starts_with($signal->method, $role.'_')) : null;
            if (! $review && $historicalSignal) {
                $review = [
                    'decision' => substr($historicalSignal->method, strlen($role) + 1),
                    'decided_at' => $historicalSignal->created_at,
                ];
            }
            $decision = $review['decision'] ?? null;
            // Older requests have no recorded decision details; infer only the outcome.
            if (! $review && $role) {
                $decision = match (true) {
                    $progressState === $role.'_approved' => 'approve',
                    $isDenied => 'deny',
                    $isReturned => 'return',
                    $role === 'manager' && (str_starts_with($progressState, 'head_') || str_starts_with($progressState, 'fo_')) => 'approve',
                    $role === 'head' && str_starts_with($progressState, 'fo_') => 'approve',
                    default => null,
                };
            }
            $reviewerId = $role ? ($dashboard->{$role.'_id'} ?? null) : null;
            $reviewerName = $review['name'] ?? $progressReviewers->get($reviewerId)?->name ?? __('Not assigned');
            $decisionDate = isset($review['decided_at']) ? \Carbon\Carbon::parse($review['decided_at'])->timezone(config('app.timezone')) : null;
        @endphp
        <li class="min-w-0 md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block">
            <div class="min-w-7 min-h-7 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-xs align-middle">
                <span class="size-7 flex justify-center items-center shrink-0 {{ $bgColor }} font-medium {{ $textColor }} rounded-full {{ $darkBgColor }} {{ $isCompleted ? 'dark:text-white' : '' }}">
                    {{ $i }}
                </span>
                <div class="mt-2 w-px h-full md:mt-0 md:ms-2 md:w-full md:h-px md:flex-1 bg-gray-200 group-last:hidden dark:bg-neutral-700"></div>
            </div>
            <div class="grow md:grow-0 md:mt-3 pb-5">
                <span class="block text-sm font-medium {{ $describeText }} dark:text-white">
                    @if($i == 1)
                        {{__("Submitted")}}
                    @elseif($i == 2)
                        {{__("Projectleader/Supervisor Approval")}}
                    @elseif($i == 3)
                        {{__("Unit Head Approval")}}
                    @elseif($i == 4)
                        {{__("Financial Officers Approval") }}
                    @endif
                </span>
                @if(in_array($i, [2, 3], true))
                    <div class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900/40">
                        <p class="break-words text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $reviewerName }}</p>
                        @if($review)
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Latest decision') }}</p>
                        @endif
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            @if(isset($decisionLabels[$decision]))
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $decisionClasses[$decision] }}">
                                    {{ $decisionLabels[$decision] }}
                                </span>
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Pending') }}</span>
                            @endif
                        </div>
                        @if($decisionDate)
                            <time datetime="{{ $decisionDate->toIso8601String() }}" class="mt-2 block text-xs text-gray-500 dark:text-gray-400">
                                {{ $decisionDate->format('Y-m-d H:i') }}
                            </time>
                        @elseif($decision)
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Date unavailable') }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </li>
    @endfor
</ul>
