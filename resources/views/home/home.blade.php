<div class="mx-auto max-w-screen-xl px-4 py-6 sm:px-6 md:py-8 lg:px-8">
    <div class="grid grid-cols-12 gap-2 xl:gap-4">
        <section class="col-span-12 grid gap-2 md:order-2 md:col-span-6 lg:col-span-4 xl:gap-4">
            @include('home.partials.middle')
        </section>

        <section class="col-span-12 grid gap-2 md:order-1 md:col-span-6 lg:col-span-4 xl:gap-4">
            @include('home.partials.top_left')
            @include('home.partials.middle_left')
        </section>

        <section class="col-span-12 grid gap-2 md:order-3 md:col-span-6 md:grid-cols-2 lg:col-span-4 lg:grid-cols-1 xl:gap-4">
            @include('home.partials.newdsv')
            @nocache('home.partials.top_right')
        </section>
    </div>
</div>

<script>
    (() => {
        const middleHolder = document.getElementById('middleHolder');
        const lectureRoomHolder = document.getElementById('lectureroomHolder');

        if (!middleHolder || !lectureRoomHolder) {
            return;
        }

        window.addEventListener('contentChanged', (event) => {
            const showLectureRooms = Boolean(event.detail?.lecturerooms);

            middleHolder.classList.toggle('hidden', showLectureRooms);
            lectureRoomHolder.classList.toggle('hidden', !showLectureRooms);
        });
    })();
</script>
