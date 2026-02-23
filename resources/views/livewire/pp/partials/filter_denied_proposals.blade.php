<div class="flex justify-center sm:justify-end">
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 mb-2 bg-white dark:bg-gray-800 relative shadow-sm rounded-md overflow-hidden">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0 md:space-x-3 p-2">
            <div class="hs-tooltip flex items-center gap-x-2">
                <!-- Denied -->
                <label for="hs-tooltip-denied" class="hs-tooltip-toggle relative inline-block w-8 h-4 cursor-pointer">
                    <input wire:model.live="hideDenied" type="checkbox" id="hs-tooltip-denied" class="peer sr-only">
                    <span class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-blue-600 dark:bg-neutral-700 dark:peer-checked:bg-blue-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 w-3.5 h-3.5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                </label>
                <!-- Denied label -->
                <label for="hs-tooltip-denied" class="text-xs text-gray-500 dark:text-neutral-400 whitespace-nowrap">Hide denied</label>
                <!-- Tooltip  -->
                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-0.5 px-1.5 bg-gray-900 text-xs font-medium text-white rounded shadow-2xs dark:bg-neutral-700" role="tooltip">
                    Enable denied Proposals
                </div>
            </div>

            <div class="hs-tooltip flex items-center gap-x-2">
                <!-- Archived -->
                <label for="hs-tooltip-archived" class="hs-tooltip-toggle relative inline-block w-8 h-4 cursor-pointer">
                    <input wire:model.live="hideArchived" type="checkbox" id="hs-tooltip-archived" class="peer sr-only">
                    <span class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-blue-600 dark:bg-neutral-700 dark:peer-checked:bg-blue-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 w-3.5 h-3.5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                </label>
                <!-- Archived label -->
                <label for="hs-tooltip-archived" class="text-xs text-gray-500 dark:text-neutral-400 whitespace-nowrap">Hide Archived</label>
                <!-- Tooltip  -->
                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-0.5 px-1.5 bg-gray-900 text-xs font-medium text-white rounded shadow-2xs dark:bg-neutral-700" role="tooltip">
                    Enable archived Proposals
                </div>
            </div>
        </div>
    </div>
</div>






