<!-- Granted -->
@if(
    in_array($dashboard->state, ['sent','denied'])
    && ! ($type == 'view' && $dashboard->state == 'sent')
)


    <div id="rejected" class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
        Please upload your decision letter
    </div>
    <livewire:pp.proposal-decision-uploader  :proposal="$proposal" :type="$type" />
@endif
