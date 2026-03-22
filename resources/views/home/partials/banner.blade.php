@foreach(\Statamic\Statamic::tag('collection:banner') as $banner)
    @if($banner->visible == true)
        {{--}}
        <div x-data="{
                bannerVisible: false,
                bannerVisibleAfter: 300,
            }"
             x-show="bannerVisible"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="-translate-y-10"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="-translate-y-10"
             x-init="
                setTimeout(()=>{ bannerVisible = true }, bannerVisibleAfter);
            "
             class="relative max-w-screen-xl mx-auto px-4 h-10 h-10 sm:px-6 md:pt-8 lg:px-8 duration-300 ease-out bg-white sm:py-0 sm:h-5 dark:bg-gray-800 dark:border-gray-700" x-cloak>

            <div class="flex items-center justify-between w-full h-full px-3 mx-auto max-w-7xl">

                <a href="{{$banner->url}}" class="flex flex-col w-full h-full text-xs leading-6 text-black duration-150 ease-out sm:flex-row sm:items-center opacity-80 hover:opacity-100">
                    <span class="flex items-center">
                        <strong class="font-semibold text-blue-700 dark:text-gray-200">{!! $banner->title !!}</strong><span class="hidden w-px h-4 mx-3 rounded-full sm:block bg-neutral-200"></span>
                    </span>
                    <span class="block pt-1 pb-2 leading-none sm:inline sm:pt-0 sm:pb-0 dark:text-gray-200">{!! $banner->content !!}</span>
                </a>
                <button @click="bannerVisible=false; " class="flex items-center flex-shrink-0 translate-x-1 ease-out duration-150 justify-center w-6 h-6 p-1.5 text-black rounded-full hover:bg-neutral-100 dark:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
        {{--}}
        <!-- Banner -->
        <div id="pp-banner" class="relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 sm:px-3.5 sm:before:flex-1">
            <div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
                <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-30"></div>
            </div>
            <div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
                <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-30"></div>
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <p class="text-sm/6 text-gray-900">
                    <strong class="font-semibold">{!! $banner->title !!}</strong><svg viewBox="0 0 2 2" aria-hidden="true" class="mx-2 inline size-0.5 fill-current"><circle r="1" cx="1" cy="1" /></svg>
                    {!! $banner->content !!}
                </p>
                {{--}}
                <a href="https://projectproposals.dsv.su.se"
                   class="flex-none rounded-full bg-gray-900 px-3.5 py-1 text-sm font-semibold text-white shadow-xs hover:bg-gray-700
                   focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    Project Proposals <span aria-hidden="true">&rarr;</span>
                </a>
                {{--}}
            </div>
            <div class="flex flex-1 justify-end">
                <button id="pp-banner-dismiss" type="button" class="-m-3 p-3 focus-visible:-outline-offset-4">
                    <span class="sr-only">Dismiss</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 text-gray-900">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- end Banner -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const banner = document.getElementById('pp-banner');
                const btn = document.getElementById('pp-banner-dismiss');

                if (banner && btn) {
                    btn.addEventListener('click', () => banner.classList.add('hidden'));
                }
            });
        </script>
    @endif
@endforeach
