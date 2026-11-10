<section class="home-card home-card--helpdesk" aria-labelledby="home-helpdesk-title">
    <span class="home-icon home-icon--violet"><x-home.icon name="support" /></span>
    <h2 id="home-helpdesk-title">DSV Helpdesk</h2>
    <div class="home-contact-list">
        <a href="tel:+468161648" class="home-contact"><span>{{ __('Phone') }}</span><strong>08-16 1648 <span aria-hidden="true">↗</span></strong></a>
        <a href="mailto:helpdesk@dsv.su.se" class="home-contact"><span>{{ __('Email') }}</span><strong>helpdesk@dsv.su.se <span aria-hidden="true">↗</span></strong></a>
        <a href="tel:+468161999" class="home-contact"><span>SU</span><strong>08-16 1999 <span aria-hidden="true">↗</span></strong></a>
    </div>
    <x-home.localized-link slug="support" class="home-text-link">
        {{ __('More ways to get help') }}
    </x-home.localized-link>
</section>
