<div class="w-full">

    <label for="oh_cost" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $isEu ? __('Percent OH cost covered EU/Wallenberg') : __('Percent OH cost covered') }}
        <span class="text-red-600"> *</span>
        <button id="oh_cost-button" data-modal-toggle="oh_cost-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>


    <!-- OH  -->

    @if($progress)
    <div class="max-w-40 flex items-center gap-x-1">
        @php
            $bars = $progress_25 ? 1 : ($progress_50 ? 2 : ($progress_75 ? 3 : ($progress_100 ? 4 : 0)));
        @endphp

        @for ($i = 0; $i < 4; $i++)
            <div class="w-full h-2.5 flex flex-col justify-center overflow-hidden
           {{ $i < $bars ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-300 dark:bg-neutral-600' }}
           transition duration-500"
                role="progressbar"
                aria-valuenow="{{ min(100, ($i+1)*25) }}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        @endfor


        <div class="w-10 text-end">
            <span class="text-sm text-gray-800 dark:text-white">{{$ohcost}}%</span>
        </div>
    </div>
    @endif
    <!-- End OH Progress -->

    <!-- Exceed OH -->
    @if($exceed)
        <div class="mt-5 max-w-40 flex items-center gap-x-1">
            <div class="w-full h-2.5 flex flex-col justify-center overflow-hidden bg-red-600 text-xs text-white text-center whitespace-nowrap transition duration-500"
                 role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="w-full h-2.5 flex flex-col justify-center overflow-hidden bg-red-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-red-500/30"
                 role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="w-full h-2.5 flex flex-col justify-center overflow-hidden bg-red-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-red-500/30"
                 role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="w-full h-2.5 flex flex-col justify-center overflow-hidden bg-red-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-red-500/30"
                 role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div>
            <div class="w-40 text-start">
                <span class="text-sm text-red-500">OH cost exceeds DSV budget</span>
            </div>
        </div>
    @endif
    <!-- End Step Progress -->
    <div  class="flex items-center w-full">
        <input type="number"
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
        @error('ohcost')
        <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">{{__("This should not exceed OH costs of DSV 56%")}}</p>
        @enderror
    </div>
</div>
