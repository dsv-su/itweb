<div class="w-full sm:col-span-2">
    <label for="currency"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Currency") }}<span class="text-red-600"> *</span>
        <button id="currency-button" data-modal-toggle="currency-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <div class="grid grid-cols-3 gap-2">
        <label for="currency" class="flex p-2 w-full bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
            <input type="radio" name="currency" value="sek"
                   class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                   id="currency"
                   @if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
                   checked required
                   @elseif(in_array($type, ['review', 'view', 'sent', 'granted']) && ($proposal['pp']['currency'] ?? '') == 'sek')
                   checked
                   @endif
                   @unless(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
                   disabled
                @endunless
            >

            <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">SEK</span>
        </label>

        <label for="hs-checkbox-checked-in-form" class="flex p-2 w-full bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
            <input type="radio" name="currency" value="usd"
                   class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                   id="currency"
                   @if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
                   required
                   @elseif(!in_array($type, ['review', 'view', 'sent', 'granted']) || ($proposal['pp']['currency'] ?? '') != 'usd')
                   disabled
                   @endif
                   @if(in_array($type, ['complete', 'review', 'view', 'granted']) && ($proposal['pp']['currency'] ?? '') == 'usd')
                   checked
                @endif>

            <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">$</span>
        </label>

        <label for="hs-checkbox-checked-in-form" class="flex p-2 w-full bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
            <input type="radio" name="currency" value="eur"
                   class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                   id="currency"
                   @if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
                   required
                   @elseif(!in_array($type, ['review', 'view', 'sent', 'granted']) || ($proposal['pp']['currency'] ?? '') != 'eur')
                   disabled
                   @endif
                   @if(in_array($type, ['complete', 'review', 'view']) && ($proposal['pp']['currency'] ?? '') == 'eur')
                   checked
                @endif>

            <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">€</span>
        </label>
    </div>
</div>
