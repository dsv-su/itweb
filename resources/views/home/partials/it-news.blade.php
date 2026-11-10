<section class="home-card home-card--news" aria-labelledby="home-news-title">
    <div class="home-card__heading">
        <span class="home-icon home-icon--blue"><x-home.icon name="news" /></span>
        @can('access cp')
            <a href="/cp/collections/itnews" aria-label="{{ __('IT news admin') }}" class="home-admin-link">
                <x-home.icon name="edit" />
            </a>
        @endcan
    </div>
    <h2 id="home-news-title">{{ __('Information from DSV IT') }}</h2>
    @nocache('home.partials.itnews')
</section>
