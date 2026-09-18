<div class="min-w-0">
    <div class="mb-2 flex min-h-6 items-center gap-1 text-sm font-medium text-gray-900 dark:text-white">
        <label id="project-label" for="project-select">{{ __('Project') }}</label>
        @include('requests.travel.partials.form.help-button', ['modal' => 'project', 'label' => __('Project')])
    </div>
    <div class="relative w-full" x-data="{open:false}" x-on:click.away="open=false" @keydown.escape.stop.prevent="open=false; $refs.trigger.focus()">
        <button id="project-select" @error('project') aria-invalid="true" @enderror aria-describedby="@if($showProjectWarning && !$Project->exists) project-missing-warning @endif @error('project') project-error @enderror" x-ref="trigger" aria-labelledby="project-label project-value" :aria-expanded="open.toString()" aria-controls="project-options" type="button" class="font-mono bg-gray-50 dark:bg-gray-700 border border-gray-500 dark:border-gray-400 text-gray-900 dark:text-gray-200 p-2.5 rounded-lg shadow-inner w-full flex justify-between items-center gap-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:focus-visible:outline-blue-300" x-on:click="open=!open; if(open) $nextTick(() => $refs.search.focus())">
            <span id="project-value" class="min-w-0 break-words text-left">{{$Project->project ?? __("Select Project")}}</span>
            <svg aria-hidden="true" class="h-4 w-4 shrink-0 transform fill-current text-black dark:text-gray-200" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 129 129" :class="{'rotate-180': open}">
                <g>
                    <path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"/>
                </g>
            </svg>
        </button>
        <div id="project-options" class="absolute left-0 top-full z-40 mt-1 w-full min-w-0 rounded-lg border border-gray-300 bg-white shadow-lg" x-show="open" x-cloak>
            <ul class="m-0 max-h-64 list-none space-y-1 overflow-y-auto p-2 text-sm">
                <li>
                    <input x-ref="search" aria-label="{{ __('Search Project') }}" wire:model.live="search" wire:keydown.enter="save" @keydown.enter.prevent="open = false; $refs.trigger.focus()" type="text" class="border border-gray-500 bg-white text-gray-900 rounded h-10 w-full p-2">
                </li>
                @forelse ($options as $item)
                <li>
                        <button type="button" aria-pressed="{{ $Project->id == $item->id ? 'true' : 'false' }}" wire:click="select({{$item->id}})" x-on:click="open=false; $refs.trigger.focus()" id="Project-{{$item->id}}" class="text-left p-2 w-full min-w-0 rounded-md text-black hover:bg-gray-100 flex justify-between items-start gap-2 cursor-pointer @if($Project->id == $item->id) bg-gray-200 @endif">
                        <span class="min-w-0 flex-1">
                            <span class="bg-blue-100 text-blue-800 inline-block max-w-full break-all text-xs font-medium mb-1 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                            {{$item->project}}
                            </span>
                            <span class="block break-words text-sm leading-5 text-gray-700">{{ $item->description }}</span>
                        </span>
                        @if ($Project->id == $item->id)
                        <svg aria-hidden="true" class="mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M6.61 11.89L3.5 8.78 2.44 9.84 6.61 14l8.95-8.95L14.5 4z"/></svg>
                        @endif
                    </button>
                </li>
                @empty
                <li id="no-Project">
                    <p class="p-2 text-gray-600">{{__('No Projects founds')}}</p>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
    <div aria-live="polite" aria-atomic="true">
        @if($showProjectWarning && !$Project->exists)
            @include('requests.travel.partials.form.project-warning', ['warningId' => 'project-missing-warning'])
        @endif
    </div>
    @include('requests.travel.partials.form.field-error', ['field' => 'project'])
    <input  name="project" value="{{$Project->project}}" hidden>
</div>
