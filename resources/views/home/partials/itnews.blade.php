<div class="home-news-list">
    @foreach (\Statamic\Statamic::tag('collection:itnews')->limit(5)->fetch()->sortByDesc('date') as $entry)
        <a href="{{ $entry['url'] }}" class="home-news-item">
            <span class="home-news-item__date">{{ \Carbon\Carbon::parse($entry['date'])->locale(app()->getLocale())->translatedFormat('j F Y') }}</span>
            <span class="home-news-item__title">{{ $entry['title'] }}<span class="home-news-item__arrow" aria-hidden="true">↗</span></span>
        </a>
    @endforeach
</div>
<a href="{{ route('newslist', ['lang' => app()->getLocale(), 'collection' => 'itnews']) }}" class="home-button">
    {{ __('More news') }} <span aria-hidden="true">→</span>
</a>
