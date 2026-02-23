<div class="max-w-screen-xl mx-auto px-4 py-6 sm:px-6 lg:px-8 md:pt-8 md:pb-8 ">
    <!-- Grid -->
    <div class="grid grid-cols-12 gap-2 xl:gap-4">
        <div class="col-span-12 md:col-span-6 md:order-2 lg:col-span-4 grid gap-2 xl:gap-4">

            <!-- Card middle-->
            @include('home.partials.middle')
            <!-- End Card -->

        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-4 md:order-1  grid gap-2 xl:gap-4">

            <!-- Card left top -->
            @include('home.partials.top_left')
            <!-- End Card -->

            <!-- Card left middle -->
            @include('home.partials.middle_left')
             <!-- End card -->

            <!-- Card left bottom-->
            @include('home.partials.bottom_left')
            <!-- End Card -->

        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-4 md:order-3 md:grid-cols-2 lg:grid-cols-1 grid gap-2 xl:gap-4">

            <!-- Card right top -->
            @nocache('home.partials.top_right')
            <!-- End Card -->

            <!-- Card right middle-->
            @include('home.partials.middle_right')
            <!-- End Card -->

            <!-- Card right bottom -->
            @include('home.partials.bottom_right')
            <!-- End Card bottom-->

            <!-- Mobile links -->
            @include('home.partials.moblie_links.daisy')
            @include('home.partials.moblie_links.forum')
            @include('home.partials.moblie_links.handledning')
            @include('home.partials.moblie_links.ilearn')
            @include('home.partials.moblie_links.otrs')
            @include('home.partials.moblie_links.play')
            @include('home.partials.moblie_links.project_proposals')
            <!-- end mobile links -->

        </div>

    </div>

</div>

<script>
    let holder = document.getElementById('middleHolder');
    let holderinfo = document.getElementById('lectureroomHolder');
    window.addEventListener('contentChanged', e => {
        if(e.detail.lecturerooms) {
            holder.style.display = 'none';
            holderinfo.style.display = 'block';
        }
        else {
            holder.style.display = 'block';
            holderinfo.style.display = 'none';
        }

    })
</script>
