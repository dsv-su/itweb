<div>
    <label id="projectleader-label" for="projectleader-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __("Projectleader/Supervisor") }}<span aria-hidden="true" class="text-red-600"> *</span><span class="sr-only">{{ __('(required)') }}</span>
        <button id="projectleader-button"
                data-modal-target="projectleader-modal"
                data-modal-toggle="projectleader-modal" class="inline-flex min-h-6 min-w-6 items-center justify-center" aria-label="{{ __('Help: Projectleader/Supervisor') }}" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <div class="relative w-full min-w-0" x-data="{open:false, selectedProjectleader: null}" x-on:click.away="open=false" @keydown.escape.stop.prevent="open=false; $refs.trigger.focus()">
        <button id="projectleader-select" @error('project_leader') aria-invalid="true" aria-describedby="project_leader-error" @enderror x-ref="trigger" aria-labelledby="projectleader-label projectleader-value" :aria-expanded="open.toString()" aria-controls="projectleader-options" type="button" class="font-mono bg-gray-50 dark:bg-gray-700 border border-gray-500 dark:border-gray-400 text-gray-900 dark:text-gray-200 p-2.5 rounded-lg shadow-inner w-full flex justify-between items-center text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:focus-visible:outline-blue-300" x-on:click="open=!open; if(open) $nextTick(() => $refs.search.focus())">
            <span id="projectleader-value" class="min-w-0 break-words text-left" x-text="selectedProjectleader ?? @js($projectleader->name ?? __("Select Projectleader/Supervisor"))"></span>
            <svg class="h-4 transform float-right fill-current text-black dark:text-gray-200" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 129 129" :class="{'rotate-180': open}">
                <g>
                    <path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"/>
                </g>
            </svg>
        </button>
        <div id="projectleader-options" class="absolute z-10 w-full rounded shadow-md bg-white" x-show="open" x-cloak>
            <ul class="list-reset p-2 max-h-64 overflow-y-auto text-sm">
                <li>
                    <input x-ref="search" aria-label="{{ __('Search Projectleader/Supervisor') }}" wire:model.live="search" wire:keydown.enter="save" @keydown.enter.prevent="open = false; $refs.trigger.focus()" type="text" class="border border-gray-500 bg-white text-gray-900 rounded h-10 w-full p-2">
                </li>
                @forelse ($options as $item)
                    <li>
                        <button type="button" aria-pressed="{{ $projectleader->id == $item->id ? 'true' : 'false' }}" wire:click="select('{{$item->id}}')"  x-on:click="open=false; $refs.trigger.focus(); selectedProjectleader=@js($item->name)" id="projectleader-{{$item->id}}" class="text-left p-2 w-full text-black hover:bg-gray-300 flex justify-between items-center cursor-pointer @if($projectleader->id == $item->id) bg-gray-200 @endif">
                            <span>{{$item->name}}</span>
                            @if ($projectleader->id == $item->id)
                                <svg class="float-right" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M6.61 11.89L3.5 8.78 2.44 9.84 6.61 14l8.95-8.95L14.5 4z"/>
                                </svg>
                            @endif
                        </button>
                    </li>
                @empty
                    <li x-on:click="open=false" id="no-Country">
                        <p class="p-2 block text-red-800 hover:bg-red-200 cursor-pointer" value="0">{{__('No projectleaders/supervisors found')}}</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    @include('requests.travel.partials.form.field-error', ['field' => 'project_leader'])
    <input  name="project_leader" value="{{$projectleader->id}}" hidden>
</div>
