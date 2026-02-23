{{--}}
<div wire:poll.visible.keep-alive.15s>
    <div class="px-1 py-1 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-12 lg:px-2 lg:py-1">
        <div class="grid grid-cols-2 gap-2 {{ $awaiting ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-2 md:gap-1">
            @php
                $slug = request()->route('slug');
            @endphp

            @if($awaiting )
                <a href="{{ route('pp.show', 'awaiting') }}"
                   class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                          transition duration-200 ease-out
                          hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                          active:translate-y-0 active:shadow-sm
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2
                          dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                          {{ $slug === 'awaiting'
                                ? 'border-blue-600/80 dark:border-blue-400/80'
                                : 'border-susecondary/40 dark:border-susecondary/40' }}">
                    <h6 class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-300">
                        {{ $awaiting ?? 0 }}
                    </h6>
                    <p class="text-xs font-medium tracking-wide text-gray-700 dark:text-gray-200 uppercase lg:text-xs">
                        Awaiting review
                    </p>
                </a>
            @endif

            <a href="{{route('pp.show', 'my')}}"
                   class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                          transition duration-200 ease-out
                          hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                          active:translate-y-0 active:shadow-sm
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2
                          dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                          {{ $slug === 'my'
                                ? 'border-blue-600/80 dark:border-blue-400/80'
                                : 'border-susecondary/40 dark:border-susecondary/40' }}">
                <h6 class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-300">
                    {{$myCount}}
                </h6>
                <p class="text-xs font-medium tracking-wide text-gray-700 dark:text-gray-200 uppercase lg:text-xs">
                    My Proposals
                </p>
            </a>
            <a href="{{route('pp.show', 'all')}}"
               class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                          transition duration-200 ease-out
                          hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                          active:translate-y-0 active:shadow-sm
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2
                          dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                          {{ $slug === 'all'
                                ? 'border-blue-600/80 dark:border-blue-400/80'
                                : 'border-susecondary/40 dark:border-susecondary/40' }}">
                <h6 class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-300">
                    {{$allCount}}
                </h6>
                <p class="text-xs font-medium tracking-wide text-gray-700 dark:text-gray-200 uppercase lg:text-xs">
                    Proposals
                </p>
            </a>
            <div class="group block text-center rounded-lg px-3 py-3
                          dark:bg-gray-900 dark:border-susecondary/40 dark:focus-visible:ring-offset-gray-900">
                <h6 class="text-sm font-bold lg:text-base dark:text-gray-200">
                    {{$sent ?? 0}}
                </h6>
                <p class="text-xs font-medium tracking-wide text-gray-700 dark:text-gray-200 uppercase lg:text-xs">
                    Sent applications
                </p>
            </div>
        </div>
    </div>
</div>
{{--}}
<div wire:poll.visible.keep-alive.15s>
    @php
        $slug = request()->route('slug');

        $isAwaiting = ($slug === 'awaiting');
        $isMy       = ($slug === 'my');
        $isAll      = ($slug === 'all');
    @endphp

    <section
        class="px-1 py-1 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-12 lg:px-2 lg:py-1"
        aria-labelledby="proposal-summary-title"
    >
        <h2 id="proposal-summary-title" class="sr-only">Proposal summary</h2>

        {{-- Optional: announce updates from polling (kept SR-only to avoid noise) --}}
        <div class="sr-only" aria-live="polite" aria-atomic="true">
            <span wire:loading>Updating summary…</span>
        </div>

        <ul
            class="grid grid-cols-2 md:gap-1 gap-2 {{ $awaiting ? 'md:grid-cols-4' : 'md:grid-cols-3' }}"
            role="list"
            aria-label="Proposal summary navigation"
        >
            @if($awaiting)
                <li>
                    <a
                        href="{{ route('pp.show', 'awaiting') }}"
                        class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                               motion-reduce:transition-none transition duration-200 ease-out
                               hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                               active:translate-y-0 active:shadow-sm
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
                               dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                               {{ $isAwaiting ? 'border-blue-600/80 dark:border-blue-400/80' : 'border-susecondary/40 dark:border-susecondary/40' }}"
                        @if($isAwaiting) aria-current="page" @endif
                    >
                        <span class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                            {{ $awaiting ?? 0 }}
                        </span>
                        <span class="block text-xs font-medium tracking-wide text-gray-800 dark:text-gray-200 uppercase lg:text-xs">
                            Awaiting review
                        </span>
                        <span class="sr-only">
                            {{ $isAwaiting ? ' (current page)' : '' }}
                        </span>
                    </a>
                </li>
            @endif

            <li>
                <a
                    href="{{ route('pp.show', 'my') }}"
                    class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                           motion-reduce:transition-none transition duration-200 ease-out
                           hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                           active:translate-y-0 active:shadow-sm
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
                           dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                           {{ $isMy ? 'border-blue-600/80 dark:border-blue-400/80' : 'border-susecondary/40 dark:border-susecondary/40' }}"
                    @if($isMy) aria-current="page" @endif
                >
                    <span class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                        {{ $myCount }}
                    </span>
                    <span class="block text-xs font-medium tracking-wide text-gray-800 dark:text-gray-200 uppercase lg:text-xs">
                        My proposals
                    </span>
                    <span class="sr-only">
                        {{ $isMy ? ' (current page)' : '' }}
                    </span>
                </a>
            </li>

            <li>
                <a
                    href="{{ route('pp.show', 'all') }}"
                    class="group block text-center rounded-lg border bg-white px-3 py-3 shadow-sm
                           motion-reduce:transition-none transition duration-200 ease-out
                           hover:-translate-y-0.5 hover:shadow-md hover:border-susecondary/70
                           active:translate-y-0 active:shadow-sm
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
                           dark:bg-gray-900 dark:focus-visible:ring-offset-gray-900
                           {{ $isAll ? 'border-blue-600/80 dark:border-blue-400/80' : 'border-susecondary/40 dark:border-susecondary/40' }}"
                    @if($isAll) aria-current="page" @endif
                >
                    <span class="text-sm font-bold lg:text-base dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                        {{ $allCount }}
                    </span>
                    <span class="block text-xs font-medium tracking-wide text-gray-800 dark:text-gray-200 uppercase lg:text-xs">
                        Proposals
                    </span>
                    <span class="sr-only">
                        {{ $isAll ? ' (current page)' : '' }}
                    </span>
                </a>
            </li>

            <li>
                {{-- Not a link: treat as a read-only metric --}}
                <div
                    class="block text-center rounded-lg border border-susecondary/40 bg-white px-3 py-3 shadow-sm
                           dark:bg-gray-900 dark:border-susecondary/40"
                    role="group"
                    aria-label="Sent applications"
                >
                    <span class="text-sm font-bold lg:text-base dark:text-gray-200">
                        {{ $sent ?? 0 }}
                    </span>
                    <span class="block text-xs font-medium tracking-wide text-gray-800 dark:text-gray-200 uppercase lg:text-xs">
                        Sent applications
                    </span>
                </div>
            </li>
        </ul>
    </section>
</div>
