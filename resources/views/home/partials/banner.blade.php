@foreach(\Statamic\Statamic::tag('collection:banner') as $banner)
    @if($banner->visible == true)
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
    @endif
@endforeach
