<header class="md:hidden block w-full fixed flex flex-col bg-white z-10">
    <nav id="site-menu" class="flex flex-col sm:flex-row w-full justify-between items-center px-4 sm:px-6 py-1 bg-white shadow sm:shadow-none">
        <div class="w-full sm:w-auto self-start sm:self-center flex flex-row sm:flex-none flex-no-wrap justify-between items-center">

            {{--}}<h1 class="tracking-widest">{{__("Submenu")}}</h1>{{--}}
            <button id="menuBtn" class="hamburger block sm:hidden focus:outline-none" type="button" onclick="navToggle();">
                <span class="hamburger_sub_top"></span><span class="hamburger_sub_bottom"></span>
            </button>
        </div>
        <div id="menu" class="w-full sm:w-auto self-end sm:self-center sm:flex flex-col sm:flex-row items-center h-full py-1 pb-4 sm:py-0 sm:pb-0 hidden z-20">
            @foreach(collect($entry['children']) as $child)
                @if($child['is_current'])
                    @foreach(collect($child['children']) as $sec_child)
                        @if(!$sec_child['children'])
                            @if($loop->first)
                                <div class="flex flex-shrink-0">
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
        </div>
    </nav>
</header>

<script>
    // Open close mobile submenu
    var nav = document.getElementById('site-menu');

    /* Remove submenu when scrolling */
    window.addEventListener('scroll', function() {
        if (window.scrollY >= 10) {
            nav.classList.add('hidden');
        } else {
            nav.classList.remove('hidden');
        }
    });

    function navToggle() {
        var btn = document.getElementById('menuBtn');
        var nav = document.getElementById('menu');

        btn.classList.toggle('open');
        nav.classList.toggle('flex');
        nav.classList.toggle('hidden');
    }
</script>

