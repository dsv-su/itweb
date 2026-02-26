<div class="w-full overflow-x-hidden">
    <div class="mb-4 space-y-4 rounded-lg p-4 sm:p-6 dark:bg-gray-900 text-black dark:text-white max-w-screen-lg w-full mx-auto">
        <div class="pointer-events-auto rounded-lg bg-white p-4 text-sm shadow-xl shadow-black/5 hover:bg-slate-50 ring-1 ring-blue-500 dark:bg-gray-900 text-black dark:text-white w-full">
            <!-- -->
            <div x-data="{
                        activeAccordion: '',
                        setActiveAccordion(id) {
                            this.activeAccordion = (this.activeAccordion == id) ? '' : id
                        }
                        }" class="relative w-full mx-auto overflow-hidden text-sm font-normal bg-white border border-gray-200 divide-y divide-gray-200 rounded-md">
                <div x-data="{ id: $id('accordion') }" class="cursor-pointer group">
                    <button @click.prevent="setActiveAccordion(id)" class="flex items-center justify-between w-full p-4 text-left select-none group-hover:underline">

                        <div class="flex items-center gap-2 flex-wrap break-words">
                            <h2 class="text-xs font-semibold text-gray-900 dark:text-white">Research Area:</h2>
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-300/20 break-words max-w-full">
                                {{$proposal->pp['research_area']}}
                            </span>
                        </div>

                        <svg class="w-4 h-4 duration-200 ease-out" :class="{ 'rotate-180': activeAccordion==id }" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="activeAccordion==id" x-collapse x-cloak>
                        <div class="p-4 pt-0 opacity-70">
                            <!-- Budget Grid -->
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Total committed</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                        dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-300/20 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['preapproved'] }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Committed(SEK)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20
                                        dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-500/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['budget_sek'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Committed(€)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20
                                        dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-500/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['budget_eur'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Committed($)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20
                                        dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-500/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['budget_usd'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Cofinancing needed</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['cost_sek'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">PHD years</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['phd'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Granted(SEK)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['granted_sek'] ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Granted(EUR)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['granted_eur'] ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Granted(USD)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->research_area[$proposal->pp['research_area']]['granted_usd'] ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Days until submission</h2>
                                    @if($proposal->pp['submission_deadline'] ?? false)
                                        @php
                                            $raw = $proposal->pp['submission_deadline'] ?? null;
                                            $deadline = null;

                                            if (!empty($raw)) {
                                                // Add formats you want to accept here:
                                                $formats = [
                                                    'Y-m-d',        // 2026-02-06
                                                    'Y/m/d',        // 2026/02/06
                                                    'd-m-Y',        // 06-02-2026
                                                    'd/m/Y',        // 06/02/2026
                                                    'Y-m-d H:i:s',  // 2026-02-06 13:45:00
                                                    'c',            // ISO8601 / RFC3339, e.g. 2026-02-06T13:45:00+01:00
                                                ];

                                                foreach ($formats as $fmt) {
                                                    try {
                                                        $deadline = \Carbon\Carbon::createFromFormat($fmt, $raw);
                                                        break;
                                                    } catch (\Throwable $e) {
                                                        // try next format
                                                    }
                                                }

                                                // Fallback: let Carbon try to parse common strings like "2026-02-06", "Feb 6, 2026", etc.
                                                if (!$deadline) {
                                                    try {
                                                        $deadline = \Carbon\Carbon::parse($raw);
                                                    } catch (\Throwable $e) {
                                                        $deadline = null;
                                                    }
                                                }
                                            }

                                            $daysLeft = $deadline ? now()->diffInDays($deadline, false) : null;
                                        @endphp

                                        @if ($daysLeft > 0)
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                                bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                                dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-400/30">
                                                {{ $daysLeft }} days left
                                            </span>
                                        @elseif ($daysLeft === 0)
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                                bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20
                                                dark:bg-yellow-900 dark:text-yellow-200 dark:ring-yellow-400/30">
                                                Deadline is today!
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                                bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10
                                                dark:bg-red-900 dark:text-red-200 dark:ring-red-400/30">
                                                Deadline passed {{ abs($daysLeft) }} days ago
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                            bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10
                                            dark:bg-blue-900 dark:text-blue-200 dark:ring-blue-400/30">
                                            N/A
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ id: $id('accordion') }" class="cursor-pointer group">
                    <button @click.prevent="setActiveAccordion(id)" class="flex items-center justify-between w-full p-4 text-left select-none group-hover:underline">
                        <span>DSV</span>
                        <svg class="w-4 h-4 duration-200 ease-out" :class="{ 'rotate-180': activeAccordion==id }" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="activeAccordion==id" x-collapse x-cloak>
                        <div class="p-4 pt-0 opacity-70">
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Commited</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->preapproved_total }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Budget(SEK)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_dsv_total_sek }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Budget(EUR)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_dsv_total_eur }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Budget(USD)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_dsv_total_usd }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Project Budget(SEK)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_project_total_sek }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Project Budget(EUR)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_project_total_eur }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Project Budget(USD)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                        bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                        dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                        {{ $budget->budget_project_total_usd }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Cofinancing needed(SEK)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                    dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                    {{ $budget->cost_total_sek }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Cofinancing needed(EUR)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                    dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                    {{ $budget->cost_total_eur }}
                                    </span>
                                </div>
                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">Cofinancing needed(USD)</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                    dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                    {{ $budget->cost_total_usd }}
                                    </span>
                                </div>

                                <div class="break-words">
                                    <h2 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">PHD years</h2>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                                    dark:bg-green-900 dark:text-green-200 dark:ring-green-400/30 break-words max-w-full">
                                    {{ $budget->phd_total }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ id: $id('accordion') }" class="cursor-pointer group">
                    <button @click.prevent="setActiveAccordion(id)" class="flex items-center justify-between w-full p-4 text-left select-none group-hover:underline">
                        <div class="space-y-3 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:gap-4 w-full">

                            <div class="flex items-center gap-2 flex-wrap break-words">
                                <h2 class="text-xs font-semibold text-gray-900 dark:text-white">Reviewer:</h2>
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-transparent text-blue-700 ring-1 ring-inset ring-blue-700/10
                                    dark:bg-transparent dark:text-blue-200 dark:ring-blue-300/20 break-words max-w-full">
                                    {{$reviewer->name}}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap break-words">
                                <h2 class="text-xs font-semibold text-gray-900 dark:text-white">State:</h2>
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-transparent text-blue-700 ring-1 ring-inset ring-blue-700/10
                                    dark:bg-transparent dark:text-blue-200 dark:ring-blue-300/20 break-words max-w-full">
                                    @switch($dashboard->state)
                                                    @case('complete')
                                                    <svg class="mr-1 shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" style="color: green;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            <path d="m9 12 2 2 4-4"></path>
                                        </svg>
                                                    Complete proposal
                                                    @break
                                                    @case('head_approved')
                                                    <svg class="mr-1 shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" style="color: green;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            <path d="m9 12 2 2 4-4"></path>
                                        </svg>
                                                    Approved by vice head and unit head
                                                    @break
                                                    @case('fo_approved')
                                                    <svg class="mr-1 shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" style="color: green;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            <path d="m9 12 2 2 4-4"></path>
                                        </svg>
                                                    Approved by Economy
                                                    @break
                                                    @default
                                                    <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" style="color: red;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                                        <path d="M12 9v4"></path>
                                                        <path d="M12 17h.01"></path>
                                                    </svg>
                                                    {{$dashboard->state}}
                                                    @break
                                                @endswitch
                                </span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap break-words">
                                <h2 class="text-xs font-semibold text-gray-900 dark:text-white">Next:</h2>
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                    bg-transparent text-blue-700 ring-1 ring-inset ring-blue-700/10
                                    dark:bg-transparent dark:text-blue-200 dark:ring-blue-300/20 break-words max-w-full">
                                    {{$role}}
                                </span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 duration-200 ease-out" :class="{ 'rotate-180': activeAccordion==id }" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="activeAccordion==id" x-collapse x-cloak>
                        <div class="p-4 pt-0 opacity-70">
                            This is handling states
                        </div>
                    </div>
                </div>
            </div>
            <!-- -->
        </div>

    </div>
</div>
