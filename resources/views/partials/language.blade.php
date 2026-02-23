@php
    $slug = request()->route('slug');

    $isAwaiting = ($slug === 'awaiting');
    $isMy       = ($slug === 'my');
    $isAll      = ($slug === 'all');
@endphp
@if(!($isAwaiting || $isMy || $isAll))
    <!-- Language switcher -->
    <button data-tooltip-target="navbar-dropdown-languageswitch-tooltip" type="button" data-dropdown-toggle="language-dropdown-menu" class="md:opacity-100 opacity-0 flex items-center text-xs font-small w-24 h-6 mx-5 {{--}}outline outline-offset-2 outline-1{{--}} rounded justify-center px-4 py-2 text-sm text-white dark:text-white cursor-pointer dark:hover:bg-gray-700 dark:hover:text-white">
        @if(Illuminate\Support\Facades\App::currentLocale() == 'sv')
            <img src="{{asset('images/globallinks-lang-sv.gif')}}" alt="Swedish flag" class="w-5 h5 mr-2">
            Svenska
        @else
            <img src="{{asset('images/globallinks-lang-en.gif')}}" alt="English flag" class="w-5 h5 mr-2">
            English
        @endif
    </button>
    <!-- Dropdown -->
    <div class="md:opacity-100 opacity-0 z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100  shadow dark:bg-gray-700" id="language-dropdown-menu">
        <ul class="py-2 font-medium" role="none">
            @foreach(['en', 'sv'] as $lang)

                @if($lang != Illuminate\Support\Facades\App::currentLocale())
                    <li>
                        <a href="{{route('language', ['lang' => $lang])}}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                            <div class="inline-flex items-center">
                                @if($lang == 'sv')
                                    <img src="{{asset('images/globallinks-lang-sv.gif')}}" alt="Swedish flag" class="w-5 h5 mr-2">
                                    Svenska
                                @else
                                    <img src="{{asset('images/globallinks-lang-en.gif')}}" alt="English flag" class="w-5 h5 mr-2">
                                    English
                                @endif

                            </div>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    <!-- end language switcher -->
@endif
