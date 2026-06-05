<article class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-md dark:border-gray-700 dark:bg-gray-800 sm:p-6">
    @include('requests.travel.progress')

    @php
        $commentUsers = collect([
            $tr->managercomment->user_id ?? null,
            $tr->headcomment->user_id ?? null,
            $tr->focomment->user_id ?? null,
        ])->filter()->unique()->values();

        $reviewers = $commentUsers->isNotEmpty()
            ? \App\Models\User::query()->whereIn('id', $commentUsers)->get()->keyBy('id')
            : collect();

        $comments = [
            [
                'label' => __('Project leader'),
                'comment' => $tr->manager_comment_id ? ($tr->managercomment->comment ?? null) : null,
                'user_id' => $tr->manager_comment_id ? ($tr->managercomment->user_id ?? null) : null,
                'updated_at' => $tr->manager_comment_id ? ($tr->managercomment->updated_at ?? null) : null,
                'tone' => 'blue',
            ],
            [
                'label' => __('Unit Head'),
                'comment' => $tr->head_comment_id ? ($tr->headcomment->comment ?? null) : null,
                'user_id' => $tr->head_comment_id ? ($tr->headcomment->user_id ?? null) : null,
                'updated_at' => $tr->head_comment_id ? ($tr->headcomment->updated_at ?? null) : null,
                'tone' => 'amber',
            ],
            [
                'label' => __('FO'),
                'comment' => $tr->fo_comment_id ? ($tr->focomment->comment ?? null) : null,
                'user_id' => $tr->fo_comment_id ? ($tr->focomment->user_id ?? null) : null,
                'updated_at' => $tr->fo_comment_id ? ($tr->focomment->updated_at ?? null) : null,
                'tone' => 'emerald',
            ],
        ];

        $comments = array_values(array_filter($comments, fn ($comment) => filled($comment['comment'] ?? null)));

        $badgeClass = [
            'blue' => 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-400/30 dark:bg-blue-400/10 dark:text-blue-200',
            'amber' => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-200',
            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-200',
        ];

        $dotClass = [
            'blue' => 'bg-blue-500 ring-blue-100 dark:ring-blue-500/20',
            'amber' => 'bg-amber-500 ring-amber-100 dark:ring-amber-500/20',
            'emerald' => 'bg-emerald-500 ring-emerald-100 dark:ring-emerald-500/20',
        ];
    @endphp

    @if(count($comments) > 0)
        <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">
            <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-5">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ __('Comments') }}
                </h2>

                <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-gray-200 bg-white px-2 text-xs font-semibold text-gray-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    {{ count($comments) }}
                </span>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($comments as $comment)
                    @php
                        $reviewer = $comment['user_id'] ? ($reviewers->get($comment['user_id'])?->name ?? __('Unknown user')) : __('Unknown user');
                        $date = $comment['updated_at'] ? \Carbon\Carbon::parse($comment['updated_at'])->format('Y-m-d') : null;
                        $tone = $comment['tone'];
                    @endphp

                    <section class="relative grid gap-3 px-4 py-4 sm:grid-cols-[10rem_1fr] sm:px-5">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full ring-4 {{ $dotClass[$tone] }}"></span>

                            <div class="min-w-0">
                                <span class="inline-flex max-w-full items-center rounded-md border px-2 py-1 text-xs font-semibold {{ $badgeClass[$tone] }}">
                                    {{ $comment['label'] }}
                                </span>

                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reviewer }}
                                    @if($date)
                                        <span class="mx-1 text-gray-300 dark:text-gray-600">/</span>
                                        <time datetime="{{ $date }}">{{ $date }}</time>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <blockquote class="min-w-0 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm leading-6 text-gray-800 shadow-sm dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <p class="whitespace-pre-line break-words">{{ $comment['comment'] }}</p>
                        </blockquote>
                    </section>
                @endforeach
            </div>
        </section>
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
