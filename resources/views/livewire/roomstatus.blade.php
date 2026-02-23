<div wire:poll.visible>
    <section>
        <div class="relative items-center w-full px-3 py-1 mx-auto md:px-6 lg:px-10 max-w-7xl">
            <div class="grid w-full grid-cols-1 mx-auto">
                @foreach(\Statamic\Statamic::tag('collection:roomsstatus') as $page)
                <div class="max-w-md py-2">
                    @can('access cp')
                        <div class="inline-flex z-10">
                            <a href="{{$page->edit_url}}" >
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1" d="m14.3 4.8 2.9 2.9M7 7H4a1 1 0 0 0-1 1v10c0 .6.4 1 1 1h11c.6 0 1-.4 1-1v-4.5m2.4-10a2 2 0 0 1 0 3l-6.8 6.8L8 14l.7-3.6 6.9-6.8a2 2 0 0 1 2.8 0Z"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                    <p class="mb-1 text-sm font-semibold text-blue-600 dark:text-gray-200">{!! $page->title !!}</p>

                    <div class="inline">
                        <p class="inline-flex mb-2 text-sm font-normal dark:text-gray-200">{{__("Projector")}} |</p>
                        <p class="inline-flex mb-2 text-sm font-normal dark:text-gray-200">{{__("Recorder")}} |</p>
                        <p class="inline-flex mb-2 text-sm font-normal dark:text-gray-200">{{__("Room")}}</p>
                    </div>

                    <br>
                    <a href="{{ config('app.url')}}{{$page->slug}}" class="inline-flex">
                        @if($page->projector == true )
                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Error")}}</span>
                        @elseif(!empty($page->projector_status))
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Remark")}}</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">OK</span>
                        @endif
                    </a>

                    <a href="{{ config('app.url')}}{{$page->slug}}" class="inline">
                        @if($page->recorder == true )
                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Error")}}</span>
                        @elseif(!empty($page->recorder_status))
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Remark")}}</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">OK</span>
                        @endif
                    </a>
                    <a href="{{ config('app.url')}}{{$page->slug}}" class="inline">
                        @if($page->room == true )
                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Error")}}</span>
                        @elseif(!empty($page->room_status))
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Remark")}}</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">OK</span>
                        @endif
                    </a>
                </div>

                @endforeach
            </div> <!-- end grid -->


        </div>
    </section>

<!-- Tooltips -->
    <div id="first" role="tooltip"
         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
         style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
         data-popper-placement="top">{{__("Projector status")}}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    <div id="second" role="tooltip"
         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
         style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
         data-popper-placement="top">{{__("Recording status")}}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    <div id="third" role="tooltip"
         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
         style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
         data-popper-placement="top">{{__("Room status")}}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

</div>
