@foreach (\Statamic\Statamic::tag('collection:news')->limit(5)->fetch()->sortByDesc('date') as $entry)
    <div>
        <div class="mt-4 text-gray-900 text-[0.65rem] dark:text-gray-300">
            {{ $entry['date'] }}  {{-- $entry['author']->name ?? '' --}}
        </div>
        <a href="{{$entry['url']}}" class="inline text-left items-center gap-x-1.5 text-blue-800 text-[0.85rem] dark:text-gray-200">
            {{ $entry['title'] }}
            <svg class="inline w-2.5 h-2.5 dark:text-gray-200" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
            </svg>
        </a>
    </div>
@endforeach
<div class="grid grid-cols-1 mt-2">
    <a href="{{app()->getLocale()}}/newslist/{{ $entry['collection'] }}" class="inline-flex items-center justify-center gap-x-1.5 text-blue-800 font-medium py-2 px-4 border border-susecondary rounded-lg w-full text-center dark:text-white">
        {{__("More news")}}
        <svg class="w-2.5 h-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
    </a>
</div>
