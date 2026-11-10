<div class="home-dashboard">
    <div class="home-dashboard__inner">
        <div class="home-grid">
            <div class="home-column home-column--news">
                @include('home.partials.it-news')
            </div>

            <div class="home-column home-column--support">
                @include('home.partials.helpdesk')
                @include('home.partials.service-status')
            </div>

            <div class="home-column home-column--people">
                @include('home.partials.new-at-dsv')
                @nocache('home.partials.people-search')
            </div>
        </div>
    </div>
</div>
