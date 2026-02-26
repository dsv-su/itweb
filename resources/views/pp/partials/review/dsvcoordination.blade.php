<div class="w-full sm:col-span-2">
    <label for="dsvcoordinationg"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Is DSV coordinating") }}<span class="text-red-600"> *</span>
        <button id="dsvcoordinationg-button" data-modal-toggle="dsvcoordinationg-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>

    <!-- Flex container for radio buttons and input -->
    <div class="flex gap-4">
        <!-- Radio buttons (1/2 width) -->
        <div class="flex w-1/2 gap-4">
            <!-- Radio button - Yes -->
            <label for="dsvcoordinating" class="flex items-center p-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 w-full">
                <input type="radio" name="dsvcoordinating" value="yes"
                       class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                       id="dsvcoordinating" @if($proposal['pp']['dsvcoordinating'] == 'yes') checked="" @endif disabled>

                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Yes</span>
            </label>

            <!-- Radio button - No -->
            <label for="hs-checkbox-checked-in-form" class="flex items-center p-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 w-full">
                <input type="radio" name="dsvcoordinating" value="no"
                       class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                       id="dsvcoordinating" @if($proposal['pp']['dsvcoordinating'] == 'no') checked="" @endif disabled>
                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">No</span>
            </label>
        </div>
        <!--  -->
        <div class="@if($proposal['pp']['dsvcoordinating'] == 'yes') hidden @else block @endif font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                        block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
            {{$proposal['pp']['other_coordination']}}
        </div>
    </div>
</div>
