<div class="w-full">

    <label for="oh_cost" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $isEu ? __('Percent of OH cost covered EU/Wallenberg') : __('Percent of OH cost covered') }}
        <span class="text-red-600"> *</span>
        <button id="oh_cost-button"
                data-modal-target="oh_cost-modal"
                data-modal-toggle="oh_cost-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>


    <div  class="flex items-center w-full">
        <input id="oh_cost" type="number"
               aria-describedby="oh-cost-status"
               wire:model.live="ohcost"
               name="oh_cost"
               class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                      block w-[calc(100%-32px)] p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                      dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="OH cost"
               @if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
               required
               @else
               readonly
            @endif >
        <span class="inline-block ml-2 text-gray-900 dark:text-gray-200">%</span>
    </div>

    {{-- Reserve space so Livewire updates never move the input or the next row. --}}
    <div id="oh-cost-status" class="h-16 pt-2" aria-live="polite" aria-atomic="true">
        @if($progress || $exceed)
            @php
                $bars = $exceed ? 4 : ($progress_25 ? 1 : ($progress_50 ? 2 : ($progress_75 ? 3 : ($progress_100 ? 4 : 0))));
            @endphp
            <div class="flex items-center gap-3">
                <div class="flex w-32 gap-1" role="meter"
                     aria-label="{{ __('OH cost coverage') }}"
                     aria-valuenow="{{ min($max, max(0, $ohcost)) }}"
                     aria-valuemin="0" aria-valuemax="{{ $max }}"
                     aria-valuetext="{{ $ohcost }}%{{ $exceed ? ' — ' . __('OH cost exceeds DSV budget') : '' }}">
                    @for ($i = 0; $i < 4; $i++)
                        <span class="h-1.5 flex-1 rounded-full transition-colors duration-300
                            {{ $exceed ? 'bg-red-500 dark:bg-red-400' : ($i < $bars ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-200 dark:bg-gray-600') }}"></span>
                    @endfor
                </div>
                <span class="text-xs tabular-nums {{ $exceed ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">{{ $ohcost }}%</span>
            </div>
            @if($exceed)
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ __('OH cost exceeds DSV budget') }}</p>
            @endif
        @endif
    </div>

    @error('ohcost')
        <p class="text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">{{__("This should not exceed OH costs of DSV 56%")}}</p>
    @enderror
</div>
