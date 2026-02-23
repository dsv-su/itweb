<div>
    <div class="relative grow max-w-96 mr-2 md:order-none">
        <div class="relative flex items-center">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                 <svg class="w-5 h-5 text-blue-800 dark:text-gray-200" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round">
                    </path>
                 </svg>
            </span>
            </div>
            <input wire:model.live="query"
                   wire:keydown.escape="resetData"
                   wire:keydown.tab="resetData"
                   wire:keydown.arrow-up="decrementHighlight"
                   wire:keydown.arrow-down="incrementHighlight"
                   wire:keydown.enter="selectUser"
                   id="search"
                   name="search"
                   class="w-full py-2 pl-10 pr-4 text-black bg-white border border-susecondary focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-500
                sm:text-sm rounded-xl placeholder:text-blue-800 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-200 dark:placeholder:text-gray-200"
                   placeholder="{{__("Search SUKAT")}}"
                   type="search">

            <div wire:loading class="animate-spin inline-block size-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-gray-200"
                 role="status"
                 aria-label="loading">
                <span class="sr-only">Searching...</span>
            </div>
        </div>


        @if(!empty($query))
            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetData"></div>
            <div class="origin-top-right absolute md:left-0 mt-2 z-20 w-72 md:w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 dark:text-white ring-1 ring-black ring-opacity-5 text-left">
                <div class="py-1 text-sm text-gray-700 dark:text-white text-left">
                    @if(!empty($sukatusers))
                        @foreach($sukatusers as $i => $users)
                            @if($users['edupersonaffiliation'] ?? null)
                                @if(is_array($users['edupersonaffiliation']))
                                    <a wire:click="selectUser({{$i}})" class="block py-2 hover:bg-gray-100 hover:text-gray-900 {{ $highlightIndex === $i ? 'dark:text-black bg-gray-100 dark:bg-gray-300' : 'dark:text-gray-200' }}
                                        dark:hover:bg-gray-300 dark:hover:text-black">
                                        <span class="px-4">{{ $users['cn'][0] }}</span>
                                        @if(in_array('employee', $users['edupersonaffiliation']))
                                            <div class="inline-block px-3 py-1 text-gray-900 font-normal bg-blue rounded-full text-xs">
                                                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs border border-gray-800 text-gray-800 dark:border-neutral-200 dark:text-white">
                                                    STAFF
                                                </span>
                                            </div>
                                        @endif
                                        @if(in_array('alum', $users['edupersonaffiliation']))
                                            <div class="inline-block px-3 py-1 text-gray-900 font-normal bg-blue rounded-full text-xs">
                                                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs border border-gray-800 text-gray-800 dark:border-neutral-200 dark:text-white">
                                                    ALUMN
                                                </span>
                                            </div>
                                        @endif
                                        @if(in_array('other', $users['edupersonaffiliation']))
                                            <div class="inline-block px-3 py-1 text-gray-900 font-normal bg-blue rounded-full text-xs">
                                                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs border border-gray-800 text-gray-800 dark:border-neutral-200 dark:text-white">
                                                    OTHER
                                                </span>
                                            </div>
                                        @endif
                                        @if(is_array($users['ou'] ?? null))
                                            <div class="py-1 px-4 text-gray-900 font-bold bg-blue rounded-full text-xs">
                                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                                    {{$users['ou'][0]}}
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
    @if($person)
        <div class="mx-auto mt-4 flex max-w-xs flex-col text-left rounded-xl border px-4 py-4 md:max-w-lg md:flex-row md:items-start md:text-left">
            <div class="">
                <p class="text-left font-normal text-gray-900 dark:text-gray-200">{{$person['displayname'][0] ?? ''}}</p>
                <p class="mb-2 text-sm text-left font-medium text-blue-700 dark:text-gray-200">{{$person['title'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">{{$person['ou'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Room: {{$person['roomnumber'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Phone: {{$person['telephonenumber'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Email: {{$person['mail'][0] ?? ''}}</p>
            </div>
        </div>
    @endif
</div>
