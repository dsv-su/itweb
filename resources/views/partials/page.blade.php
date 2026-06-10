<div class="flex w-full flex-1 flex-col">
    <main class="relative flex-1 overflow-y-auto focus:outline-none">
        <div class="py-6 sm:py-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="py-4">
                    <article class="prose prose-slate mt-4 mb-4 max-w-none rounded-xl bg-white p-6 font-sans text-gray-700 antialiased shadow-xl prose-headings:tracking-tight prose-a:text-blue-700 prose-a:underline-offset-2 prose-img:rounded-lg dark:prose-invert dark:bg-gray-800 dark:text-gray-100 dark:prose-a:text-blue-300 sm:p-8 lg:p-10">
                        <h1 class="mb-4 text-3xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-4xl">
                            {!! $page->title !!}
                        </h1>

                        @if(isset($page->intro))
                            <p class="mt-4 max-w-3xl text-lg leading-8 text-gray-600 dark:text-gray-200">
                                {!! $page->intro !!}
                            </p>
                        @endif

                        @foreach($page->content as $content)
                            @if($content->type == "text")
                                <div class="overflow-x-auto leading-7 text-gray-700 dark:text-gray-100">
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
                                        <img class="h-auto w-full" src="{{ $image->url }}" alt="" loading="lazy" />
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

                        <div class="not-prose mt-10 border-t border-gray-200 pt-4 text-sm leading-6 text-gray-500 dark:border-gray-700 dark:text-gray-300">
                            <p><span class="font-medium">{{ __("Responsible for the page:") }}</span> {{ $page->author->name ?? 'NN' }}</p>
                            <p><span class="font-medium">{{ __("Last edited:") }}</span> {{ $page->last_modified }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>
</div>
