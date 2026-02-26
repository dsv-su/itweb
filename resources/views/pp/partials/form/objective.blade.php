<div class="sm:col-span-2">
    <label for="objective" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Outline of the Proposal. Write a short summary of the research goals (max. 500 characters).") }}<span class="text-red-600"> *</span>
        <button id="objective-button" data-modal-toggle="objective-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <textarea id="objective" rows="4" name="objective"
              class="@error('objective') border-red-500 @enderror font-mono block p-2.5 w-full text-sm text-gray-900
                                  @if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif rounded-lg border border-gray-300
                                  focus:ring-blue-500 focus:border-blue-500
                                  @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif
                  dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="{{__("Outline of the Proposal")}}" @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete', 'resume'])) required="" @else readonly @endif>{{ old('objective') ? old('objective'): $proposal['pp']['objective'] ?? '' }}</textarea>
    @error('objective')
    <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">{{__("This is a required input")}}</p>
    @enderror
    <p class="mt-1 text-xs">
        <span id="objective-count" class="text-gray-500 dark:text-gray-300">0</span>/500
    </p>
    <script>
        const ta = document.getElementById('objective');
        const c  = document.getElementById('objective-count');
        const max = 500;

        function updateCount() {
            const len = ta.value.length;
            c.textContent = len;

            // make the number red if it exceeds the limit
            c.classList.toggle('text-red-600', len > max);
            c.classList.toggle('text-gray-500', len <= max);
            c.classList.toggle('dark:text-red-400', len > max);
            c.classList.toggle('dark:text-gray-300', len <= max);
        }

        ta.addEventListener('input', updateCount);
        updateCount();
    </script>

</div>
