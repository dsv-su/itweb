<video controls playsinline preload="metadata" class="aspect-video h-auto w-full rounded-lg bg-black"
       aria-label="{{ __('Video') }}">
    <source src="{{ asset('test.mp4') }}" type="video/mp4" />
    <a href="{{ asset('test.mp4') }}">{{ __('Download video') }}</a>
</video>
