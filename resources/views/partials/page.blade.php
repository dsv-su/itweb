<div class="flex w-full flex-1 flex-col">
    <main class="relative flex-1 overflow-y-auto focus:outline-none">
        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 md:px-6">
                <div class="py-4">
                    <article class="prose mt-4 mb-4 max-w-none rounded-xl bg-white p-8 antialiased shadow-xl dark:prose-invert dark:bg-gray-800 dark:text-white">
                        <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {!! $page->title !!}
                        </h2>

                        @if(isset($page->intro))
                            <h4 class="mt-4 max-w-xl text-base tracking-tight text-gray-600 dark:prose-invert dark:text-white">
                                {!! $page->intro !!}
                            </h4>
                        @endif

                        @foreach($page->content as $content)
                            @if($content->type == "text")
                                <!-- Mobile: allow horizontal scroll if content has wide tables/code -->
                                <div class="md:hidden overflow-x-auto text-xs dark:text-white">
                                    {!! $content->text !!}
                                </div>

                                <!-- Desktop -->
                                <div class="hidden md:block dark:text-white">
                                    {!! $content->text !!}
                                </div>

                            @elseif($content->type == "fileassets")
                                <ul class="space-y-3 text-xs">
                                    @foreach($content->file as $file)
                                        <li class="flex space-x-3">
                                            <a
                                                href="{{$file->url}}"
                                                class="inline-flex items-center justify-center rounded-lg bg-gray-50 p-3 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                            >
                                                <svg class="mr-2 h-6 w-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 19">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 15h.01M4 12H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-3M9.5 1v10.93m4-3.93-4 4-4-4"/>
                                                </svg>

                                                <span class="text-gray-800 dark:text-gray-400">
                                                    @if($content->file_description)
                                                        {{$content->file_description}}
                                                    @else
                                                        {{$file->filename}}
                                                    @endif
                                                    ({{$file->size}})
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            @elseif($content->type == "images")
                                @foreach($content->image as $image)
                                    <div class="border border-blue-600">
                                        <img class="max-w-full rounded-t-lg" src="{{$image->url}}" alt="" loading="lazy" />
                                    </div>
                                @endforeach

                            @elseif($content->type == "faq_group")
                                @include('partials.faq')

                            @elseif($content->type == "video_group")
                                <div class="mx-auto flex w-full w-[640px] min-h-[250px] items-center rounded-md py-10 box-border">
                                    @include('partials.video')
                                </div>
                            @endif
                        @endforeach

                        <div class="rounded-md bg-white p-3 text-sm leading-none text-gray-600 dark:bg-gray-800 dark:text-white">
                            <hr>
                            <p><i>{{__("Responsible for the page:")}} {!! $page->author->name ?? 'NN' !!}</i></p>
                            <p><i>{{__("Last edited:")}} {!! $page->last_modified !!}</i></p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>
</div>
