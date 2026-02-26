<div class="w-full sm:col-span-2">
    <label for="cofinancing"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Co-financing needed") }}
        <button id="cofinancing-button" data-modal-toggle="cofinancing-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>

    <!-- Flex container to align radio and input -->
    <div class="flex items-center gap-2">
        <label for="cofinancing" class="flex items-center p-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 w-1/3">
            <input type="radio"
                   class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                    checked disabled>
            <span class="text-sm text-gray-500 ml-3 dark:text-neutral-400">@if($proposal['pp']['cofinancing'] ?? '' == 'yes') Yes @else No @endif</span>
        </label>

        <!-- Inline text input next to radio -->
        @if($proposal['pp']['cofinancing'] ?? '' == 'yes')
            <textarea id="other_cofinancing" rows="4" name="other_cofinancing"
                      readonly
                      class="@if($proposal['pp']['cofinancing'] == 'yes') block @else hidden @endif font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                                  focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                {{ old('other_cofinancing') ? old('other_cofinancing'): $proposal->pp['other_cofinancing'] ??  '' }}
            </textarea>
        @endif
    </div>
</div>

