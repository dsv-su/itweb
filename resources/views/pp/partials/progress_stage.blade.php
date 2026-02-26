<!-- Stepper -->
@php
    // Dashboard state mapped to a step number
    $stateToStep = [
        'submitted' => 1,
        'complete' => 2,
        'head_approved'  => 3,
        'head_returned'  => 2,
        'head_denied'  => 0,
        'fo_approved' => 4,
        'fo_returned' => 3,
        'fo_denied' => 0,
        'final_approved' => 5,
        'final_returned' => 4,
        'sent' => 6,
        'granted' => 6,
        'denied' => 6,

    ];
    // Determine the current step based on the dashboard state (or 1 if not set)
    $currentStep = isset($dashboard) && isset($stateToStep[(string)$dashboard->state])
                     ? $stateToStep[(string)$dashboard->state]
                     : 1;
@endphp

<ul class="relative flex flex-col md:flex-row gap-2">
    @for($i = 1; $i <= 6; $i++)
        @php
            // For each step, if it's less than or equal to the current step, mark as "completed" (blue)
            $isCompleted = $i <= $currentStep;
            $bgColor = $isCompleted ? 'bg-blue-500' : 'bg-gray-100';
            $darkBgColor = $isCompleted ? 'dark:bg-blue-600' : 'dark:bg-neutral-700';
            $textColor = $isCompleted ? 'text-white' : 'text-gray-800';
            $describeText = $isCompleted ? 'text-blue-500' : 'text-gray-800';
        @endphp
        <li class="md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block">
            <div class="min-w-7 min-h-7 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-xs align-middle">
                <span class="size-7 flex justify-center items-center shrink-0 {{ $bgColor }} font-medium {{ $textColor }} rounded-full {{ $darkBgColor }} {{ $isCompleted ? 'dark:text-white' : '' }}">
                    {{ $i }}
                </span>
                <div class="mt-2 w-px h-full md:mt-0 md:ms-2 md:w-full md:h-px md:flex-1 bg-gray-200 group-last:hidden dark:bg-neutral-700"></div>
            </div>
            <div class="grow md:grow-0 md:mt-3 pb-5">
                <span class="block text-sm font-medium {{ $describeText }} dark:text-white">
                    @if($i == 1)
                        New Proposal
                    @elseif($i == 2)
                        Files uploaded
                    @elseif($i == 3)
                        Unit Head(s) Approval
                    @elseif($i == 4)
                        Financial Officers Approval
                    @elseif($i == 5)
                        Final Approval
                    @elseif($i == 6)
                        @if($describeText == $isCompleted)
                            Application sent
                        @else
                            Ready for Submission
                        @endif
                    @endif
                </span>
            </div>
        </li>
    @endfor
</ul>
