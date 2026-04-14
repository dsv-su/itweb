{{--}}
10% → Submitted
20% → Complete
40% → UHApproval
60% → Budget Review
80% → Final Approval
100% → Sent
{{--}}
@switch((string) ($proposal->dashboard?->state ?? ''))
    @case('submitted')
        @php
            $progress = 10;
            $message  = 'Upload files';
            $color    = 'bg-yellow-400';
        @endphp
        @break

    @case('complete')
        @php
            $progress = 30;
            $message  = 'Processing';
            $color    = 'bg-purple-600';
        @endphp
        @break

    @case('head_approved')
        @php
            $progress = 55;
            $message  = 'Processing';
            $color    = 'bg-blue-600';
        @endphp
        @break

    @case('fo_approved')
        @php
            $progress = 65;
            $message  = 'Processing';
            $color    = 'bg-blue-600';
        @endphp
        @break

    @case('final_approved')
        @php
            $progress = 85;
            $message  = 'Ready to send';
            $color    = 'bg-blue-600';
        @endphp
        @break

    @case('sent')
    @case('granted')
        @php
            $progress = 100;
            $message  = '';
            $color    = 'bg-green-600';
        @endphp
        @break

    @case('head_denied')
    @case('vice_denied')
    @case('fo_denied')
        @php
            $progress = 0;
            $message  = '';
            $color    = 'bg-red-600';
        @endphp
        @break

    @case('head_returned')
        @php
            $progress = 20;
            $message  = '';
            $color    = 'bg-yellow-300';
        @endphp
        @break

    @case('vice_returned')
        @php
            $progress = 15;
            $message  = '';
            $color    = 'bg-yellow-300';
        @endphp
        @break

    @case('fo_returned')
        @php
            $progress = 40;
            $message  = '';
            $color    = 'bg-yellow-300';
        @endphp
        @break

    @case('final_returned')
        @php
            $progress = 60;
            $message  = '';
            $color    = 'bg-yellow-300';
        @endphp
        @break

    @case('denied')
        @php
            $progress = 100;
            $message  = '';
            $color    = 'bg-red-600';
        @endphp
        @break

    @default
        @php
            $progress = 0;
            $message  = 'Pending';
            $color    = 'bg-yellow-300';
        @endphp
@endswitch

@php
    $steps = [
        ['label' => 'Draft',         'at' => 0],
        ['label' => 'Complete',      'at' => 20],
        ['label' => 'UH Approval',   'at' => 40],
        ['label' => 'Budget Review', 'at' => 60],
        ['label' => 'Final Approval','at' => 80],
        ['label' => 'Sent',          'at' => 100],
    ];

    $currentIndex = 0;
    foreach ($steps as $i => $s) {
        if ($progress >= $s['at']) $currentIndex = $i;
    }

    $currentStepLabel = $steps[$currentIndex]['label'] ?? 'Progress';
    $valueText = trim(($message ? $message . ' — ' : '') . $currentStepLabel . ' (' . (int)$progress . '%)');
@endphp

<div class="w-full">
    <div class="grid w-full overflow-hidden bg-transparent">

        {{-- Shared accessible semantics --}}
        <div class="sr-only" id="proposal-progress-label">
            Proposal progress
        </div>

        {{-- Screen-reader status announcement (WCAG 4.1.3) --}}
        <div class="sr-only" aria-live="polite" aria-atomic="true">
            {{ $valueText }}
        </div>

        {{-- MOBILE --}}
        <div class="md:hidden px-4 pb-4">
            <div class="rounded-md border border-gray-200 dark:border-neutral-700 bg-white/0 p-3">

                <div
                    role="progressbar"
                    aria-labelledby="proposal-progress-label"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="{{ (int) $progress }}"
                    aria-valuetext="{{ $valueText }}"
                    class="sr-only"
                ></div>

                <div class="flex items-start justify-between gap-3">
                    <div class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        Step {{ $currentIndex + 1 }} of {{ count($steps) }}: {{ $currentStepLabel }}
                    </div>

                    @if($message)
                        <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                            {{ $message }}
                        </div>
                    @endif
                </div>

                <div class="mt-2 h-2 w-full rounded-full bg-gray-300 dark:bg-neutral-700 overflow-hidden">
                    <div
                        aria-hidden="true"
                        class="h-full {{ $color }} motion-reduce:transition-none transition-all duration-500"
                        style="width: {{ $progress }}%"
                    ></div>
                </div>

                <ol class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-800 dark:text-neutral-200" aria-label="Progress steps">
                    @foreach($steps as $i => $step)
                        @php
                            $done = $progress >= $step['at'];
                            $isCurrent = $i === $currentIndex;
                        @endphp

                        <li class="flex items-center gap-2">
                            <span
                                class="h-2.5 w-2.5 rounded-full {{ $done ? $color : 'bg-gray-400 dark:bg-neutral-600' }}"
                                aria-hidden="true"
                            ></span>

                            <span @if($isCurrent) aria-current="step" @endif>
                                {{ $step['label'] }}
                                <span class="sr-only">
                                    {{ $isCurrent ? ' (current step)' : ($done ? ' (completed)' : ' (not completed)') }}
                                </span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        {{-- DESKTOP / TABLET --}}
        <div class="hidden md:block relative h-14 place-items-center">
            <div class="w-full px-4 pb-4 sm:px-10">
                <div class="relative flex items-center justify-between w-full">

                    {{-- Progressbar semantics (not visual) --}}
                    <div
                        role="progressbar"
                        aria-labelledby="proposal-progress-label"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        aria-valuenow="{{ (int) $progress }}"
                        aria-valuetext="{{ $valueText }}"
                        class="sr-only"
                    ></div>

                    <!-- Gray Line (decorative) -->
                    <div aria-hidden="true" class="absolute left-0 top-2/4 h-1 w-full -translate-y-2/4 bg-gray-400 dark:bg-neutral-700"></div>

                    <!-- Color Progress Line (decorative) -->
                    <div
                        aria-hidden="true"
                        class="absolute left-0 top-2/4 h-1 -translate-y-2/4 {{ $color }} motion-reduce:transition-none transition-all duration-500"
                        style="width: {{ $progress }}%"
                    ></div>

                    <!-- Progress Message (visual only; SR announcement handled above) -->
                    @if($message)
                        <div
                            aria-hidden="true"
                            class="absolute -top-5 text-sm font-semibold text-blue-700 dark:text-blue-300
                                   motion-reduce:transition-none transition-all duration-500 whitespace-nowrap"
                            style="left: calc({{ $progress }}% - 10px);"
                        >
                            {{ $message }}
                        </div>
                    @endif

                    <ol class="relative z-10 flex w-full justify-between" aria-label="Progress steps">
                        @foreach($steps as $i => $step)
                            @php
                                $done = $progress >= $step['at'];
                                $isCurrent = $i === $currentIndex;
                            @endphp

                            <li class="relative flex flex-col items-center">
                                <span
                                    class="grid h-3.5 w-3.5 place-items-center rounded-full
                                           {{ $done ? $color : 'bg-gray-400 dark:bg-neutral-600' }}"
                                    aria-hidden="true"
                                ></span>

                                <span class="absolute -bottom-[2.1rem] w-max text-center">
                                    <span
                                        class="uppercase font-sans text-xs antialiased font-semibold leading-relaxed tracking-normal
                                               {{ $done ? 'text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-neutral-300' }}"
                                        @if($isCurrent) aria-current="step" @endif
                                    >
                                        {{ $step['label'] }}
                                    </span>

                                    <span class="sr-only">
                                        {{ $isCurrent ? ' (current step)' : ($done ? ' (completed)' : ' (not completed)') }}
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ol>

                </div>
            </div>
        </div>

    </div>
</div>
