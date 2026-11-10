<article class="home-card text-page__article prose prose-slate dark:prose-invert" aria-labelledby="page-title">
    <div class="text-page__body">
        <header class="text-page__header">
            <h1 id="page-title">{!! $page->title !!}</h1>

            @if(isset($page->intro))
                <p class="text-page__intro">
                    {!! $page->intro !!}
                </p>
            @endif

        </header>

        @foreach($page->content as $content)
            @if($content->type == "text")
                <div class="page-rich-content">
                    {!! $content->text !!}
                </div>

            @elseif($content->type == "fileassets")
                <ul class="not-prose my-6 space-y-3">
                    @foreach($content->file as $file)
                        <li>
                            <a
                                href="{{ $file->url }}"
                                class="inline-flex max-w-full items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-gray-300 hover:bg-gray-100 hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-offset-gray-800"
                            >
                                <svg class="h-5 w-5 shrink-0 text-gray-600 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 19">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 15h.01M4 12H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-3M9.5 1v10.93m4-3.93-4 4-4-4"/>
                                </svg>

                                <span class="min-w-0 break-words">
                                    @if($content->file_description)
                                        {{ $content->file_description }}
                                    @else
                                        {{ $file->filename }}
                                    @endif
                                    <span class="whitespace-nowrap text-gray-500 dark:text-gray-400">({{ $file->size }})</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

            @elseif($content->type == "images")
                @foreach($content->image as $image)
                    <figure class="my-6 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                        <img class="h-auto w-full" src="{{ $image->url }}" alt="{{ $image->alt ?? '' }}" loading="lazy" />
                    </figure>
                @endforeach

            @elseif($content->type == "faq_group")
                @include('partials.faq')

            @elseif($content->type == "video_group")
                <div class="not-prose mx-auto flex min-h-[250px] w-full max-w-3xl items-center justify-center rounded-md py-6">
                    @include('partials.video')
                </div>
            @endif
        @endforeach

        <div class="not-prose text-page__meta">
            <p><span class="font-medium">{{ __("Responsible for the page:") }}</span> {{ $page->author->name ?? 'NN' }}</p>
            <p><span class="font-medium">{{ __("Last edited:") }}</span> {{ $page->last_modified }}</p>
        </div>
    </div>
</article>
