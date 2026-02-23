<div class="w-full sm:col-span-2">
    <!--First row -->
    <div class="w-full sm:col-span-2">
        <!-- Co investigators label -->
        <label for="coinvestigators" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Co-investigators") }}
            <button id="coinvestigators" data-modal-toggle="coinvestigators-modal" class="inline" type="button">
                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </button>
        </label>
        <!-- Rendering SUKAT Co investigators -->
        @if(count($presenters) > 0 )
            @foreach($presenters as $key => $presenter)
                <div wire:key="presenter-row-{{ $key }}"
                    class="flex flex-col md:flex-row gap-4 mb-4 items-center">
                    <!-- Name -->
                    <div
                        class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$presenter['name'] ?? 'Name is missing'}}
                        @if($presenter['role'] === 'DSV')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                            {{$presenter['role']}}</span>
                        @elseif($presenter['role'] === 'SU')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-purple-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                SU</span>
                        @elseif($presenter['role'] === 'Student')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-green-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                {{$presenter['role']}} </span>
                        @else
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-gray-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                External</span>
                        @endif
                    </div>
                    <!-- Email -->
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                    w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$presenter['email'] ?? 'Email is missing'}}
                    </div>
                    <!-- Icon -->
                    <div class="flex items-center">
                        <button type="button" wire:click="remove_presenter({{ $key }})">
                            <svg class="shrink-0 size-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Autocomplete SUKAT -->
    <div class="flex justify-start">
        <div class="relative w-full"
             x-data
             x-on:keydown.arrow-down.prevent="$wire.moveHighlight(1)"
             x-on:keydown.arrow-up.prevent="$wire.moveHighlight(-1)"
             x-on:keydown.enter.prevent="$wire.addHighlighted()"
             x-on:click.outside="$wire.set('searchPresenter','')"
             x-on:keydown.escape.prevent="$wire.set('searchPresenter','')"
        >
            <input
                id="presenter-input"
                type="text"
                class="p-3 w-full border rounded-md text-sm
             bg-gray-50 text-gray-800 placeholder:text-gray-500
             border-slate-300
             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
             dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400
             dark:border-slate-700 dark:focus:ring-blue-400"
                wire:model.live.debounce.300ms="searchPresenter"
                placeholder="{{__('Add a SUKAT co-investigator by name or email')}}"
                autocomplete="off"
                role="combobox"
                aria-expanded="{{ filled($searchPresenter) ? 'true' : 'false' }}"
                aria-controls="search-results"
            />

            @if(filled($searchPresenter))
                <div id="search-results" role="listbox"
                     class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 shadow-md rounded-md p-2 z-50
                          max-h-[calc(100vh-8rem)] overflow-y-auto
                          dark:bg-slate-900 dark:border-slate-800">
                    <ul class="space-y-2">
                        @foreach($sukatUsers as $i => $sukatUser)
                            <li wire:key="presenter-{{ $sukatUser->uid ?? $sukatUser->name }}">
                                <button
                                    type="button"
                                    role="option"
                                    class="w-full text-left border rounded-lg p-2 sm:p-2 transition
                                       border-slate-200 hover:bg-blue-50 active:bg-blue-100
                                       dark:border-slate-800 dark:hover:bg-slate-800 dark:active:bg-slate-700
                                       {{ $highlighted === $i ? 'bg-blue-100 dark:bg-slate-700' : '' }}"
                                    wire:click="addPresenter(@js($sukatUser->uid), @js($sukatUser->name), @js($sukatUser->email))"

                                    x-on:click="$wire.set('searchPresenter','')"
                                >
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-800 dark:text-white shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                        </svg>
                                        {{--}}<div class="text-xs sm:text-base leading-tight min-w-0 text-slate-900 dark:text-slate-100">
                                            <span class="font-medium">
                                                @if($sukatUser->role === 'External')
                                                    <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                            bg-gray-50 text-slate-900 dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{__('SU: ')}}
                                                    </span>
                                                @endif
                                                {{ $sukatUser->name }}

                                            </span>
                                            @php $role = $sukatUser->role ?? 'Other'; @endphp
                                            @if($role === 'DSV')
                                                <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                        bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                {{ $role }}
                                            </span>
                                            @elseif($role === 'SU')
                                                <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                           bg-purple-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                {{ $role }}
                                            </span>
                                            @elseif($role === 'Student')
                                                <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                           bg-green-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                {{ $role }}
                                            </span>
                                            @else
                                                <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                           bg-gray-200 text-gray-600 dark:bg-blue-800/30 dark:text-blue-500">
                                                External
                                            </span>
                                            @endif
                                            <span class="font-small">{{$sukatUser->email}}</span>
                                        </div>{{--}}
                                        <div class="min-w-0 text-slate-900 dark:text-slate-100">
                                            {{-- line 1: name + badges --}}
                                            <div class="flex flex-wrap items-center gap-2 leading-tight">
                                                @if($sukatUser->role === 'External')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-gray-50 text-slate-900 dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ __('SU: ') }}
                                                    </span>
                                                @endif

                                                <span class="font-medium">
                                                    {{ $sukatUser->name }}
                                                </span>

                                                @php $role = $sukatUser->role ?? 'Other'; @endphp
                                                @if($role === 'DSV')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'SU')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-purple-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'Student')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-green-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-gray-200 text-gray-600 dark:bg-blue-800/30 dark:text-blue-500">
                                                        External
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- line 2: email --}}
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 truncate">
                                                {{ $sukatUser->email }}
                                            </div>
                                        </div>

                                    </div>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- External Co investigators -->
    @if($showExternal)
        <div class="flex flex-col md:flex-row gap-4 mt-4 mb-4 items-center">
            <input type="text" wire:model.live="external_coinvestigators_name"
                   class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                      w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                   placeholder="{{ __('Type Name') }}">

            <input type="email" wire:model.live="external_coinvestigators_email"
                   class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                      w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                   placeholder="{{ __('Type Email') }}">

            <div class="flex items-center">
                <button type="button" wire:click="saveExternal"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50
                           focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white
                           dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Save
                </button>
            </div>
        </div>
    @endif

    <button wire:click="addExternal"
            class="inline mt-2 py-2 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-lg border border-blue-600 text-blue-600
          hover:border-blue-500 hover:text-blue-500 focus:outline-none focus:border-blue-500 focus:text-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400"
            type="button">
        Add external co-investigator+
    </button>

    @foreach($presenters as $i => $p)
        <input type="hidden" name="co_investigator_name[]" value="{{ $p['name'] ?? '' }}" wire:key="pr-name-{{ $i }}">
        <input type="hidden" name="co_investigator_email[]" value="{{ $p['email'] ?? '' }}" wire:key="pr-email-{{ $i }}">
        <input type="hidden" name="co_investigator_type[]" value="{{ $p['type'] ?? '' }}" wire:key="pr-type-{{ $i }}">
        <input type="hidden" name="co_investigator_role[]" value="{{ $p['role'] ?? '' }}" wire:key="pr-role-{{ $i }}">
    @endforeach
</div>





