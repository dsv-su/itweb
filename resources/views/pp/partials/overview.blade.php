<div class="mb-4 space-y-4 dark:bg-gray-900 text-black dark:text-white rounded-lg">
    <div class="pointer-events-auto rounded-lg bg-white p-4 text-[0.8125rem]/5 shadow-xl shadow-black/5 hover:bg-slate-50 ring-1 ring-blue-500 dark:bg-gray-900 text-black dark:text-white">
        <div class="lg:flex lg:items-center lg:justify-between">
            <!-- First -->
            <div class="min-w-0 flex-1">
                <h2 class="text-xs leading-5 font-semibold text-gray-900 dark:text-white">Project Status</h2>
                @switch($proposal->status_stage1)

                    @case('pending')
                    @case('head_returned')
                    @case('vice_returned')
                    @case('fo_returned')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20
                                    dark:bg-yellow-900 dark:text-yellow-200 dark:ring-yellow-400/30">
                        @break
                    @case('submitted')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                    dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-300/20">
                        @break
                    @case('head_approved')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-700/10
                                    dark:bg-purple-900 dark:text-purple-200 dark:ring-purple-300/20">
                        @break
                    @case('vice_approved')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-700/10
                                    dark:bg-indigo-900 dark:text-indigo-200 dark:ring-indigo-300/20">
                        @break
                    @case('fo_approved')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                    dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30">
                        @break
                    @case('head_denied')
                    @case('vice_denied')
                    @case('fo_denied')
                    @case('error')
                        <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10
                                    dark:bg-red-900 dark:text-red-200 dark:ring-red-400/30">
                        @break
                @endswitch
                        {{$proposal->status_stage1}}</span>
            </div>
            <!-- Second -->
            @php
                $budget = $proposal->pp['budget_dsv'];
                $max = 50000;

            @endphp
            <div class="min-w-0 flex-1">
                <h2 class="text-xs leading-5 font-semibold text-gray-900 dark:text-white">Total budget DSV</h2>
                <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                              bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20
                              dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-500/30">
                    {{$max}}
                </span>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="text-xs leading-5 font-semibold text-gray-900 dark:text-white">Budget DSV</h2>
                @if ($budget > $max)
                    <span class="inline-flex items-center uppercase rounded-md px-2 py-1 text-xs font-medium
                                  bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10
                                  dark:bg-red-900 dark:text-red-200 dark:ring-red-400/30">
                @else
                    <span class="inline-flex uppercase items-center rounded-md px-2 py-1 text-xs font-medium
                          bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                          dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30">
                @endif
                        {{$proposal->pp['budget_dsv']}} {{$proposal->pp['currency']}}
                    </span>
            </div>
            <!-- Third -->
            <div class="min-w-0 flex-1">
                <h2 class="text-xs leading-5 font-semibold text-gray-900 dark:text-white">Days until submission</h2>
                @if($proposal->pp['submission_deadline'] ?? false)
                    @php
                        $deadline = \Carbon\Carbon::createFromFormat('d/m/Y', $proposal->pp['submission_deadline']);
                        $daysLeft = now()->diffInDays($deadline, false);
                    @endphp

                    @if ($daysLeft > 0)
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                      bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                      dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-400/30">
                        {{ $daysLeft }} days left
                    @elseif ($daysLeft === 0)
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                  bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20
                                  dark:bg-yellow-900 dark:text-yellow-200 dark:ring-yellow-400/30">

                                Deadline is today!
                    @else
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                  bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10
                                  dark:bg-red-900 dark:text-red-200 dark:ring-red-400/30">

                                Deadline passed {{ abs($daysLeft) }} days ago
                    @endif
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                  bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                  dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-400/30">
                        N/A
                    </span>
                @endif
            </div>
        </div>
        @nocache('livewire.pp.partials.progress3')
    </div>
</div>



