@if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'review', 'view', 'resume', 'sent', 'granted', 'rejected']))
    <div id="proposal-attachments" class="sm:col-span-2">
        <label for="upload" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Proposal attachments") }}
            <button id="upload-button" data-modal-toggle="upload-modal" class="inline" type="button">
                <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </button>
        </label>
    </div>
    @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete']))
    <div class="mb-2 mt-4 bg-blue-50 border border-blue-500 text-sm text-gray-500 rounded-lg p-5 dark:bg-blue-600/[.15]">
        <div class="flex">
            <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
            <div class="ms-3">
                <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please note!</h3>
                <p class="mt-2 text-gray-800 dark:text-slate-400">You must upload the proposal's budget, along with a brief description of the proposal.</p>
            </div>
        </div>
    </div>
    @endif
    <livewire:pp.proposal-uploader  :proposal="$proposal" :type="$type" />

    <livewire:pp.proposal-budget-uploader  :proposal="$proposal" :type="$type" />
@endif
@if(in_array($type, ['sent']))
    @include('pp.partials.form.sent')
@endif
