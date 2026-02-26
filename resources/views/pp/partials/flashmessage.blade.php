<!-- Flash message -->
@if (session('success'))
    <div class="mx-auto max-w-screen-xl mt-4 px-4 lg:px-12">
        @include('pp.partials.flash_submitted')
    </div>
@elseif(session('error'))
    <div class="mx-auto max-w-screen-xl mt-4 px-4 lg:px-12">
        @include('pp.partials.flash_error')
    </div>
@endif
