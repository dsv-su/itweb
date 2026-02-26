<div id="sent" class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
    Sent
</div>
<div class="mb-4 mt-4 bg-blue-50 border border-blue-500 text-sm text-gray-500 rounded-lg p-5 dark:bg-blue-600/[.15]">
    <div class="flex">
        <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 16v-4"></path>
            <path d="M12 8h.01"></path>
        </svg>
        <div class="ms-3">
            <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please upload your completed final application.</h3>
            <p class="mt-2 text-gray-800 dark:text-slate-400">Before you can register your application as submitted, you need to upload the final version of your application.
                This version will be archived and forwarded to the Registrator.
            </p>
        </div>
    </div>
</div>
<livewire:pp.proposal-final-uploader  :proposal="$proposal" :type="$type" />
