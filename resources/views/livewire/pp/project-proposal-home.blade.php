<div wire:poll.visible.keep-alive.15s class="bg-gray-50 p-3 dark:bg-gray-900 sm:p-5">
    @php
        $slug = request()->route('slug');
        $sections = [
            'my' => ['label' => 'My proposals', 'count' => $myCount],
            'all' => ['label' => 'All proposals', 'count' => $allCount],
        ];
        if ($awaiting) {
            $sections = ['awaiting' => ['label' => 'Awaiting review', 'count' => $awaiting]] + $sections;
        }
    @endphp

    <div class="mx-auto max-w-7xl space-y-5">
        <section aria-labelledby="proposal-summary-title" class="overflow-hidden rounded-2xl border border-susecondary bg-white dark:border-susecondary dark:bg-gray-800">
            <div class="border-b border-susecondary px-4 py-3 dark:border-susecondary sm:px-5">
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-700 dark:text-blue-300">At a glance</p>
                <h2 id="proposal-summary-title" class="mt-1 text-base font-semibold text-gray-900 dark:text-white">Proposal overview</h2>
            </div>

            <div class="sr-only" aria-live="polite" aria-atomic="true">
                <span wire:loading>Updating summary…</span>
            </div>

            <dl class="grid grid-cols-2 gap-x-5 gap-y-4 px-4 py-4 sm:px-5 {{ $awaiting > 0 ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }}">
                @foreach([
                    'My proposals' => $myCount,
                    'All proposals' => $allCount,
                    ...($awaiting > 0 ? ['Awaiting review' => $awaiting] : []),
                    'Sent applications' => $sent ?? 0,
                    'Granted proposals' => $granted,
                ] as $label => $count)
                    <div class="border-l-2 border-susecondary pl-3 dark:border-susecondary">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                        <dd class="mt-1 text-2xl font-semibold tracking-tight tabular-nums text-gray-900 dark:text-white">{{ $count }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <nav aria-label="Browse proposals" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
            <span class="mr-1 text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Browse</span>
            @foreach($sections as $key => $section)
                @php $isActive = $slug === $key || ($key === 'my' && empty($slug)); @endphp
                <a href="{{ route('pp.show', $key) }}"
                   @if($isActive) aria-current="page" @endif
                   class="inline-flex min-h-11 items-center justify-between gap-3 rounded-lg border px-4 py-2.5 text-sm font-semibold shadow-sm transition-colors motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900
                       {{ $isActive ? 'border-blue-800 bg-blue-800 text-white dark:border-blue-400 dark:bg-blue-400 dark:text-gray-950' : 'border-susecondary bg-white text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-800 dark:border-susecondary dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:bg-gray-700' }}">
                    {{ $section['label'] }}
                    <span class="rounded-md px-2 py-0.5 text-xs tabular-nums {{ $isActive ? 'bg-white/15 dark:bg-black/10' : 'bg-gray-100 dark:bg-gray-700' }}">{{ $section['count'] }}</span>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                </a>
            @endforeach
        </nav>
    </div>
</div>
