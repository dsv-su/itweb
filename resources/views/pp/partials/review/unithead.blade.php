<div class="w-full">
    @if(is_array($dashboard->unit_heads ?? []) && ($UnitHeads = count($dashboard->unit_heads ?? [])) > 1)
        @foreach($dashboard->unit_heads as $uh)
            <div class="mb-2 font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
            flex items-center w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                {{ \App\Models\User::find($uh)->name }}
                @php
                    $unitHeadApproved = is_array($dashboard->unit_head_approved)
                        ? $dashboard->unit_head_approved
                        : json_decode($dashboard->unit_head_approved, true);
                @endphp
                @foreach($unitHeadApproved as $key => $uh_approved)
                    @if($key == $uh && $uh_approved == 1)
                        <div class="ml-2 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>
                            </svg>
                            <span class="text-green-500 font-semibold">Approved</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    @else
        <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                        block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
        @if(is_array($dashboard->unit_heads ?? []) && isset($dashboard->unit_heads[0]))
            {{ \App\Models\User::find($dashboard->unit_heads[0])->name }}
        @else
                N/A
        @endif
        </div>
    @endif
</div>
