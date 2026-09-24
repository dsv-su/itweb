@if(in_array($type, ['preapproval', 'saved', 'edit', 'resume', 'complete']) && auth()->user()->canAssignPrincipalInvestigator())
    <details class="w-full sm:col-span-2 rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
             @if($errors->has('principal_investigator_uid')) open @endif>
        <summary class="cursor-pointer rounded-lg px-4 py-3 text-sm font-medium text-blue-700 hover:bg-blue-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 dark:text-blue-400 dark:hover:bg-gray-700">
            {{ __('Select another principal investigator (only possible for SystemAdmins and vice)') }}
        </summary>
        <div class="border-t border-gray-200 p-4 dark:border-gray-700">
            <livewire:pp.principal-investigator-search />
        </div>
    </details>
    <input type="hidden" name="principal_investigator_uid" id="principal_investigator_uid" value="{{ old('principal_investigator_uid') }}">
    @error('principal_investigator_uid') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    <script>
        window.addEventListener('principal-investigator-selected', (event) => {
            document.getElementById('principal_investigator_uid').value = event.detail.uid;
            document.getElementById('principal_investigator').value = event.detail.name;
            document.getElementById('principal_investigator_email').value = event.detail.email;
        });
    </script>
@endif
<div class="w-full">
    <label for="principal_investigator" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Principal Investigator at DSV") }}<span class="text-red-600"> *</span>
        <button
            id="principal_investigator-button"
            data-modal-target="principal_investigator-modal"
            data-modal-toggle="principal_investigator-modal"
            class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <input type="text" name="principal_investigator" id="principal_investigator" readonly
           class="font-mono @if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif
               border border-blue-600 text-gray-900 text-sm font-semibold rounded-lg focus:ring-primary-600 focus:border-primary-600
                    block w-full p-2.5 @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
           value="{{ old('principal_investigator') ? old('principal_investigator'): $proposal['pp']['principal_investigator'] ??  auth()->user()->name  }}" placeholder="Title" required="">
</div>
<div class="w-full">
    <label for="principal_investigator_email" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Email") }}<span class="text-red-600"> *</span>
        <button
            id="principal_investigator_email-button"
            data-modal-target="principal_investigator_email-modal"
            data-modal-toggle="principal_investigator_email-modal"
            class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <input type="text" name="principal_investigator_email" id="principal_investigator_email" readonly
           class="font-mono @if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif
               border border-blue-600 text-gray-900 text-sm font-semibold rounded-lg focus:ring-primary-600 focus:border-primary-600
                    block w-full p-2.5 @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
           value="{{ old('principal_investigator_email') ? old('principal_investigator_email'): $proposal['pp']['principal_investigator_email'] ??  auth()->user()->email  }}" placeholder="Title" required="">
</div>
