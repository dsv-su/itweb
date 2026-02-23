@foreach(collect($entry['children']) as $child)
    @if($child['is_current'])
        @foreach(collect($child['children']) as $sec_child)
            @if(!$sec_child['children'])
                @if($loop->first)
                    <!-- Mobile -->
                    @include('navbar.partials.mobile')
                    <!-- end mobile -->
                    <div class="hidden md:flex md:flex-shrink-0">
                        <div class="flex flex-col w-64">
                            <div class="flex flex-col flex-grow pt-3 overflow-y-auto bg-white border-r dark:bg-gray-800 dark:text-white">
                                <div class="flex flex-col flex-grow px-3 mt-3">
                                    <nav class="flex-1 space-y-1 bg-white dark:bg-gray-800 dark:text-white">
                                        <ul>
                                            @include('navbar.partials.first_child')
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    @endif
@endforeach
