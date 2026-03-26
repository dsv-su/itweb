<article class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-md dark:border-gray-700 dark:bg-gray-800 sm:p-6">
    @include('requests.travel.progress')

    @php
        $comments = [
            [
                'label' => __('Project leader'),
                'comment' => $tr->manager_comment_id ? ($tr->managercomment->comment ?? null) : null,
                'user_id' => $tr->manager_comment_id ? ($tr->managercomment->user_id ?? null) : null,
                'updated_at' => $tr->manager_comment_id ? ($tr->managercomment->updated_at ?? null) : null,
            ],
            [
                'label' => __('Unit Head'),
                'comment' => $tr->head_comment_id ? ($tr->headcomment->comment ?? null) : null,
                'user_id' => $tr->head_comment_id ? ($tr->headcomment->user_id ?? null) : null,
                'updated_at' => $tr->head_comment_id ? ($tr->headcomment->updated_at ?? null) : null,
            ],
            [
                'label' => __('FO'),
                'comment' => $tr->fo_comment_id ? ($tr->focomment->comment ?? null) : null,
                'user_id' => $tr->fo_comment_id ? ($tr->focomment->user_id ?? null) : null,
                'updated_at' => $tr->fo_comment_id ? ($tr->focomment->updated_at ?? null) : null,
            ],
        ];

        // remove empty comment blocks
        $comments = array_values(array_filter($comments, fn ($c) => filled($c['comment'] ?? null)));
    @endphp

    @if(count($comments) > 0)
        <h2 class="mt-4 mb-2 text-base font-bold tracking-tight text-gray-900 dark:text-white sm:text-lg">
            {{ __('Comments') }}
        </h2>
        <hr class="my-2 border-gray-200 dark:border-gray-700">

        <div class="mt-3 space-y-4">
            @foreach($comments as $c)
                <section class="space-y-2">
                    <span class="inline-flex items-center rounded border border-blue-400 bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-gray-700 dark:text-blue-400">
                        {{ $c['label'] }}
                    </span>

                    <div class="rounded-2xl border border-gray-200 bg-white p-3 dark:border-neutral-700 dark:bg-neutral-900 sm:p-4">
                        <div class="text-sm text-gray-800 dark:text-white break-words">
                            {{ $c['comment'] }}
                        </div>
                    </div>

                    <div class="flex flex-wrap items-start gap-x-1.5 gap-y-1 text-xs text-gray-500 dark:text-neutral-400">
                        <svg class="mt-0.5 size-3 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 6 7 17l-5-5"></path>
                            <path d="m22 10-7.5 7.5L13 16"></path>
                        </svg>

                        <span class="min-w-0 break-words">
                            @if($c['user_id'])
                                {{ optional(\App\Models\User::find($c['user_id']))->name ?? __('Unknown user') }}
                            @else
                                {{ __('Unknown user') }}
                            @endif
                        </span>

                        @if($c['updated_at'])
                            <span class="whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($c['updated_at'])->format('Y-m-d') }}
                            </span>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    @if($tr->state === 'fo_approved')
        <div class="mt-4">
            <a href="{{ route('travel-request-pdf', $tr->id) }}"
               class="inline-flex w-full items-center justify-center rounded-lg border border-susecondary px-4 py-2 text-center font-medium text-blue-800 dark:text-white sm:w-auto">
                {{ __('Download') }}
            </a>
        </div>
    @endif
</article>
