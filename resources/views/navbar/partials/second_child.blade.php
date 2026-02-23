@foreach(collect($child['children']) as $sec_child)
    @if(!$sec_child['children'])
        <li>
            <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-gray-900 transition duration-200 ease-in-out
                transform rounded-lg focus:shadow-outline hover:bg-gray-100 hover:scale-95 hover:text-blue-500 dark:text-white"
                href="{{ $sec_child['url'] }}">
                <span class="ml-4">
                    {{ $sec_child['title'] }}
                </span>
            </a>
        </li>
    @else
        <li>
            <div x-data="{ open: false }">
                <button class="inline-flex items-start justify-between w-full px-4 py-2 mt-1 text-sm text-gray-900 transition duration-200 ease-in-out transform rounded-lg
                    focus:shadow-outline hover:bg-gray-100 hover:scale-95 hover:text-blue-500 group dark:text-white hover:dark:text-blue-500" @click="open = ! open">
                    <span class="ml-2 text-sm">
                      {{ $sec_child['title'] }}
                    </span>
                    <svg style="fill:gray" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-5 h-5 ml-auto transition-transform duration-200 transform group-hover:text-accent rotate-0">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="p-2 pl-6 -px-px" x-show="open" @click.outside="open = false" style="display: none;">
                    <ul>
                        @include('navbar.partials.third_child')
                    </ul>
                </div>
            </div>
        </li>
    @endif
@endforeach
