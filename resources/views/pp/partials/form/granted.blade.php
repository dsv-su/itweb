<!-- Granted -->
@if(
    in_array($dashboard->state, ['sent','granted'])
    && ! ($type == 'view' && $dashboard->state == 'sent')
)
<div id="granted" class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
    Granted
</div>

<div class="w-full">
    <label for="granted" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Granted amount") }}<span class="text-red-600"> *</span>
    {{__("in ")}} {{strtoupper($proposal->pp['currency'])}}</label>
    <input type="number" name="granted"
           class="mb-2 font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                                block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
           value="{{ old('granted') ? old('granted'): $proposal->pp['granted'] ??  '' }}"
           placeholder="Granted amount" @if($type == 'granted' or $type == 'edit' or $type == 'resume') required @else readonly @endif>
    <label for="cofinanced_promised" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Cofinanced promised") }}<span class="text-red-600"> *</span>
        {{__("in ")}} {{strtoupper($proposal->pp['currency'])}}</label>
    <input type="number" name="cofinanced_promised"
           class="mb-2 font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                                block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
           value="{{ old('cofinanced_promised') ? old('cofinanced_promised'): $proposal->pp['cofinanced_promised'] ??  '' }}"
           placeholder="Cofinanced promised amount" @if($type == 'granted' or $type == 'edit' or $type == 'resume') required @else readonly @endif>
    <label for="phd_promised" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("PHD student promised years") }}<span class="text-red-600"> *</span></label>
    <input type="number" name="phd_promised"
           class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                                block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
           value="{{ old('phd_promised') ? old('phd_promised'): $proposal->pp['phd_promised'] ??  '' }}"
           placeholder="PHD student years financed" @if($type == 'granted' or $type == 'edit' or $type == 'resume') required @else readonly @endif>
</div>
<div class=" mt-2 mb-4 sm:col-span-2">
    <label for="user_comments" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Other Comments") }}</label>
    <textarea id="granted_comments" rows="4" name="granted_comments"
              class="font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="{{__("Granted comments")}}" @if($type == 'view' or $type == 'review') readonly @endif>{{ old('granted_comments') ? old('granted_comments'): $proposal->pp['granted_comments'] ?? '' }}</textarea>
</div>
<livewire:pp.proposal-decision-uploader  :proposal="$proposal" :type="$type" />
@endif
