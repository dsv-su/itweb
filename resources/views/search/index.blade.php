@extends('layouts.app')

@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')

    @php
        $searchRoute = app()->getLocale() === 'sv'
            ? route('search.localized', ['lang' => 'swe'])
            : route('search');

        $collectionStyles = [
            'pages' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-200 dark:ring-sky-400/30',
            'equipment' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-200 dark:ring-emerald-400/30',
            'it' => 'bg-amber-50 text-amber-800 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-400/30',
            'itnews' => 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-200 dark:ring-fuchsia-400/30',
            'network' => 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-200 dark:ring-fuchsia-400/30',
        ];
    @endphp

    <main class="min-h-screen bg-gray-50 text-gray-950 dark:bg-gray-900 dark:text-white">
        <section class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-950">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">
                        {{ __('Search') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-bold text-gray-950 dark:text-white sm:text-4xl">
                        {{ __('Search all contents') }}
                    </h1>
                </div>

                <form action="{{ $searchRoute }}" method="GET" role="search" class="mt-7">
                    <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:items-center">
                        <label for="site-search" class="sr-only">{{ __('Search') }}</label>
                        <input id="collection-filter" name="collection" type="hidden" value="{{ $collection }}">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input
                                id="site-search"
                                name="q"
                                type="search"
                                value="{{ $q }}"
                                autocomplete="off"
                                placeholder="{{ __('Search pages, IT guides, equipment and news') }}"
                                class="h-12 w-full rounded-lg border border-gray-300 bg-white pl-12 pr-4 text-base text-gray-950 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-500/20"
                            >
                        </div>

                        <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-susecondary bg-susecondary px-5 text-sm font-semibold text-blue-950 transition hover:bg-susecondary focus:outline-none focus:ring-4 focus:ring-blue-200 dark:border-susecondary dark:bg-susecondary dark:text-gray-950 dark:focus:ring-blue-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Search') }}
                        </button>
                    </div>

                    <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex flex-wrap justify-center gap-2" role="group" aria-label="{{ __('Filter by content type') }}">
                            @foreach($collections as $handle => $label)
                                @continue($handle === 'pages')
                                @php
                                    $isSelected = $collection === $handle;
                                    $buttonClass = $isSelected
                                        ? 'border-susecondary bg-susecondary text-blue-950 hover:bg-susecondary focus:ring-blue-200 dark:border-susecondary dark:bg-susecondary dark:text-gray-950 dark:focus:ring-blue-500/30'
                                        : 'border-gray-300 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-blue-500/20';
                                    $countLabel = $handle !== 'all' && $filterCounts->has($handle)
                                        ? '(' . $filterCounts->get($handle) . ')'
                                        : '';
                                @endphp
                                <button
                                    type="submit"
                                    name="collection"
                                    value="{{ $handle }}"
                                    onclick="document.getElementById('collection-filter').disabled = true"
                                    class="inline-flex h-10 items-center justify-center rounded-md border px-3 text-sm font-semibold transition focus:outline-none focus:ring-4 {{ $buttonClass }}"
                                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                >
                                    {{ $label }}
                                    <span class="ml-1 text-xs opacity-80">{{ $countLabel }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:max-w-3xl">
                        <div>
                            <label for="sort-filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('Sort results') }}
                            </label>
                            <select id="sort-filter" name="sort" onchange="this.form.submit()" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-950 focus:border-blue-600 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                <option value="relevance" @selected($sort === 'relevance')>{{ __('Most relevant') }}</option>
                                <option value="title" @selected($sort === 'title')>{{ __('Title') }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            @if($q !== '')
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-950 dark:text-white">
                            {{ __('Search results') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ trans_choice('{0} No results for ":query"|{1} :count result for ":query"|[2,*] :count results for ":query"', $results->count(), ['count' => $results->count(), 'query' => $q]) }}
                            @if($collection !== 'all' && $totalResults !== $results->count())
                                {{ __('Filtered from :count total results.', ['count' => $totalResults]) }}
                            @endif
                        </p>
                    </div>

                    <a href="{{ $searchRoute }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        {{ __('Clear search') }}
                    </a>
                </div>
            @endif

            @if($q === '')
                {{-- Initial search page intentionally has no empty-state panel. --}}
            @elseif($results->isEmpty())
                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-950 dark:text-white">{{ __('No results found') }}</h3>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Try another keyword or choose a broader content type filter.') }}
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($results as $result)
                        <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-500/60">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <a href="{{ $result['url'] }}" class="text-lg font-semibold text-gray-950 hover:text-blue-700 dark:text-white dark:hover:text-blue-300">
                                        {{ $result['title'] }}
                                    </a>

                                    @if($result['excerpt'] !== '')
                                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                                            {{ $result['excerpt'] }}
                                        </p>
                                    @endif
                                </div>

                                <span class="inline-flex max-w-full shrink-0 items-center self-start rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset sm:self-auto {{ $collectionStyles[$result['collection']] ?? 'bg-gray-100 text-gray-700 ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:ring-gray-600' }}">
                                    {{ $result['collection_label'] }}
                                </span>
                            </div>

                            @if($result['url'])
                                <p class="mt-3 truncate text-xs text-gray-500 dark:text-gray-400">
                                    {{ $result['url'] }}
                                </p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>
@endsection
