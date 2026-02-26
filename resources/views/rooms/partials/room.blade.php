<div class="space-y-5 md:space-y-8">
    <div class="space-y-3">
        <h2 class="text-2xl font-bold md:text-3xl dark:text-gray-200 dark:before:bg-gray-600"> {!! $page->title !!}</h2>
        <br>
        @if($page->projector === false and $page->recorder === false and $page->room ===false and empty($page->projector_status) and empty($page->recorder_status) and empty($page->room_status))
            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">{{__("Status: OK")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{{__("Nothing reported")}}</p>
        @endif

        <!-- Projector -->
        @if($page->projector)
            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Projector")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{{__("There is a malfunction with the projector")}}</p>
            <br>
        @elseif(!empty($page->projector_status))
            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Projector")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{!! $page->projector_status !!}</p>
            <br>
        @endif
        <!-- Recorder -->
        @if($page->recorder)
            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Recorder")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{{__("There is a malfunction with the recorder")}}</p>
            <br>
        @elseif(!empty($page->recorder_status))
            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Recorder")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{!! $page->recorder_status !!}</p>
            <br>
        @endif
        <!-- Room -->
        @if($page->room)
            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Room")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{{__("There is a malfunction with the room")}}</p>
        @elseif(!empty($page->room_status))
            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400">{{__("Room")}}</span>
            <p class="text-lg text-gray-800 dark:text-gray-200 dark:before:bg-gray-600">{!! $page->room_status !!}</p>
        @endif

    </div>
</div>
