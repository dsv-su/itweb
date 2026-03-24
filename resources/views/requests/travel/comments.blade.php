<article class="w-full p-4 sm:p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
    @include('requests.travel.progress')

    @if($tr->manager_comment_id || $tr->fo_comment_id || $tr->head_comment_id)
        <h2 class="mb-2 mt-4 text-base sm:text-lg font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __("Comments") }}
        </h2>
        <hr class="my-2">

        @if($tr->manager_comment_id)
            <span class="inline-flex bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                Project leader
            </span>

            <div class="pt-2">
                <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                    <div class="space-y-1">
                        <div class="text-sm text-gray-800 dark:text-white break-words">
                            {{ $tr->managercomment->comment }}
                        </div>
                    </div>
                </div>

                <span class="mt-1.5 flex items-start gap-x-1 text-xs text-gray-500 dark:text-neutral-500">
                    <svg class="shrink-0 size-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 7 17l-5-5"></path>
                        <path d="m22 10-7.5 7.5L13 16"></path>
                    </svg>
                    <div class="text-[0.7rem] leading-snug dark:text-white break-words">
                        {{ \App\Models\User::find($tr->managercomment->user_id)->name }}
                        <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($tr->managercomment->updated_at)->format('Y-m-d') }}</span>
                    </div>
                </span>
            </div>
        @endif

        @if($tr->head_comment_id)
            <span class="inline-flex mt-3 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                Unit Head
            </span>

            <div class="pt-2">
                <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                    <div class="space-y-1">
                        <div class="text-sm text-gray-800 dark:text-white break-words">
                            {{ $tr->headcomment->comment }}
                        </div>
                    </div>
                </div>

                <span class="mt-1.5 flex items-start gap-x-1 text-xs text-gray-500 dark:text-neutral-500">
                    <svg class="shrink-0 size-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 7 17l-5-5"></path>
                        <path d="m22 10-7.5 7.5L13 16"></path>
                    </svg>
                    <div class="text-[0.7rem] leading-snug dark:text-white break-words">
                        {{ \App\Models\User::find($tr->headcomment->user_id)->name }}
                        <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($tr->headcomment->updated_at)->format('Y-m-d') }}</span>
                    </div>
                </span>
            </div>
        @endif

        @if($tr->fo_comment_id)
            <span class="inline-flex mt-3 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                FO
            </span>

            <div class="pt-2">
                <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                    <div class="space-y-1">
                        <div class="text-sm text-gray-800 dark:text-white break-words">
                            {{ $tr->focomment->comment }}
                        </div>
                    </div>
                </div>

                <span class="mt-1.5 flex items-start gap-x-1 text-xs text-gray-500 dark:text-neutral-500">
                    <svg class="shrink-0 size-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 7 17l-5-5"></path>
                        <path d="m22 10-7.5 7.5L13 16"></path>
                    </svg>
                    <div class="text-[0.7rem] leading-snug dark:text-white break-words">
                        {{ \App\Models\User::find($tr->focomment->user_id)->name }}
                        <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($tr->focomment->updated_at)->format('Y-m-d') }}</span>
                    </div>
                </span>
            </div>
        @endif
    @endif

    @if($tr->state === 'fo_approved')
        <div class="grid grid-cols-1 mt-3">
            <a href="{{ route('travel-request-pdf', $tr->id) }}"
               class="inline-flex items-center justify-center gap-x-1.5 text-blue-800 font-medium py-2 px-4 border border-susecondary rounded-lg w-full text-center dark:text-white">
                {{ __("Download") }}
            </a>
        </div>
    @endif
</article>
