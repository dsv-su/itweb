{{--}}
<div class="-mt-px">
    <!-- Breadcrumb -->
    <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center py-2">
            <!-- Breadcrumb -->
            <ol class="ms-3 flex items-center whitespace-nowrap">
                <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                    Dashboard
                    <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </li>
                <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                    {{$breadcrumb}}
                </li>
            </ol>
            <!-- End Breadcrumb -->
            Status
        </div>
    </div>
    <!-- End Breadcrumb -->
</div>
{{--}}

<!-- Breadcrumb -->
{{--}}<div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 dark:bg-neutral-800 dark:border-neutral-700">
    <div class="flex items-center py-2">
        <!-- Breadcrumb -->
        <ol class="ms-3 flex items-center whitespace-nowrap">
            <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                Dashboard
                <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </li>
            <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                {{$breadcrumb}}
            </li>
        </ol>
        <!-- End Breadcrumb -->

        <!-- Move Status to the right -->
        <span class="ms-auto text-xs font-semibold text-gray-800 dark:text-neutral-400">
            <span class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-yellow-500 text-yellow-500">
                Testmode
            </span>
            @if(app()->environment('local'))
            <a type="button" href="{{route('proposal.seed')}}" class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-green-500 text-green-500">
                Seed
            </a>
            <a type="button" href="{{route('proposal.reset')}}" class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-red-500 text-red-500">
                Reset
            </a>
            @endif
            <div class="hidden md:block md:inline">
                @if($roles ?? false)
                    @foreach($roles as $role)
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-medium me-1 px-1 py-0 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 leading-none">
                        {{$role}}
                    </span>
                    @endforeach
                @endif
            </div>

        </span>
    </div>
</div>{{--}}
<!-- End Breadcrumb -->

<div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 dark:bg-neutral-800 dark:border-neutral-700">
    <div class="flex items-center py-2 gap-2">
        <!-- Breadcrumb (can shrink) -->
        <ol class="ms-3 flex items-center whitespace-nowrap min-w-0 flex-1">
            <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400 shrink-0">
                Dashboard
                <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </li>

            <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400 min-w-0" aria-current="page">
                {{$breadcrumb}}
            </li>
        </ol>

        <!-- Status (always right, never shrinks) -->
        <div class="ms-auto shrink-0 flex flex-wrap justify-end items-center gap-1 text-xs font-semibold text-gray-800 dark:text-neutral-400">
      <span class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-yellow-500 text-yellow-500">
        Testmode
      </span>

            @if(app()->environment('local'))
                <a href="{{route('proposal.seed')}}" class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-green-500 text-green-500">
                    Seed
                </a>
                <a href="{{route('proposal.reset')}}" class="inline-flex items-center gap-x-1 py-0 px-1 rounded-full text-[10px] font-medium border border-red-500 text-red-500">
                    Reset
                </a>
            @endif

            <div class="hidden md:flex md:flex-wrap md:items-center md:gap-1">
                @if($roles ?? false)
                    @foreach($roles as $role)
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-medium px-1 py-0 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 leading-none">
              {{$role}}
            </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

