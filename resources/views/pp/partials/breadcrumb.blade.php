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
<div class="sticky top-0 inset-x-0 z-20 bg-white border-b px-4 sm:px-6 lg:px-8 dark:bg-neutral-800 dark:border-neutral-700">
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

<!-- Banner -->
<div id="pp-banner" class="relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 sm:px-3.5 sm:before:flex-1">
    <div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
        <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-30"></div>
    </div>
    <div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
        <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-30"></div>
    </div>
    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
        <p class="text-sm/6 text-gray-900">
            <strong class="font-semibold">Project Proposals</strong><svg viewBox="0 0 2 2" aria-hidden="true" class="mx-2 inline size-0.5 fill-current"><circle r="1" cx="1" cy="1" /></svg>The new portal launches on April 1. Until then, use the current portal via the link.
        </p>
        <a href="https://projectproposals.dsv.su.se" class="flex-none rounded-full bg-gray-900 px-3.5 py-1 text-sm font-semibold text-white shadow-xs hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">Project Proposals <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="flex flex-1 justify-end">
        <button id="pp-banner-dismiss" type="button" class="-m-3 p-3 focus-visible:-outline-offset-4">
            <span class="sr-only">Dismiss</span>
            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 text-gray-900">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
    </div>
</div>
<!-- end Banner -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const banner = document.getElementById('pp-banner');
        const btn = document.getElementById('pp-banner-dismiss');

        if (banner && btn) {
            btn.addEventListener('click', () => banner.classList.add('hidden'));
        }
    });
</script>
