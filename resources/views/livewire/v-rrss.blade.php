<div class="text-left pb-4">
    @foreach ($rssItems as $item)
        <div class="mt-4 text-gray-900 text-[0.65rem] dark:text-gray-300">
            {{ $item['pubDate'] ?? '' }}
        </div>
        <a href="{{ $item['link'] }}"
           class="inline text-left items-center gap-x-1.5 text-blue-800 text-[0.85rem] dark:text-gray-200">
            {{ $item['title'] }}
            <svg class="inline w-2.5 h-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
            </svg>
        </a>
    @endforeach
</div>
