<section class="home-card home-card--welcome" aria-labelledby="home-new-title">
    <span class="home-icon home-icon--peach"><x-home.icon name="sparkles" /></span>
    <h2 id="home-new-title">{{ __('New at DSV') }}</h2>
    <p class="home-card__description">{{ __('Here is a collection of links to help you get started with the basics.') }}</p>
    <x-home.localized-link slug="new-at-dsv" class="home-button home-button--primary">
        {{ __('Get started') }}
    </x-home.localized-link>
</section>
