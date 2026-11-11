<ul class="news-page__list">
    @forelse($entries as $entry)
        <li>
            <a href="{{ $entry->url }}" class="home-card news-page__entry">
                <div class="news-page__meta">
                    <span>{{ $entry->date }}</span>
                    <span class="news-page__badge">{{ $entry->collection }}</span>
                </div>
                <div class="news-page__title-row">
                    <h2>{{ $entry->title }}</h2>
                    <span class="news-page__arrow" aria-hidden="true">↗</span>
                </div>
                @if($entry->author->name ?? null)
                    <p class="news-page__author">{{ $entry->author->name }}</p>
                @endif
            </a>
        </li>
    @empty
        <li class="home-card news-page__empty">{{ __('No news yet.') }}</li>
    @endforelse
</ul>
