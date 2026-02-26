<div class="w-full">
    <label for="principal_investigator" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Principal Investigator") }}<span class="text-red-600"> *</span>
        <button id="principal_investigator-button" data-modal-toggle="principal_investigator-modal" class="inline" type="button">
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
        <button id="principal_investigator_email-button" data-modal-toggle="principal_investigator_email-modal" class="inline" type="button">
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
