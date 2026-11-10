@foreach(\Statamic\Statamic::tag('collection:banner') as $banner)
    @if($banner->visible == true)
        <!-- Banner -->
        <div id="pp-banner-{{ $loop->index }}" class="relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 sm:px-3.5 sm:before:flex-1">
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
            </div>
            <div class="flex flex-1 justify-end">
                <button id="pp-banner-dismiss-{{ $loop->index }}" type="button" class="-m-3 p-3 focus-visible:-outline-offset-4">
                    <span class="sr-only">{{ __('Dismiss announcement') }}</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 text-gray-900">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- end Banner -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const banner = document.getElementById('pp-banner-{{ $loop->index }}');
                const btn = document.getElementById('pp-banner-dismiss-{{ $loop->index }}');

                if (banner && btn) {
                    btn.addEventListener('click', () => {
                        document.getElementById('main-content')?.focus();
                        banner.classList.add('hidden');
                    });
                }
            });
        </script>
    @endif
@endforeach
