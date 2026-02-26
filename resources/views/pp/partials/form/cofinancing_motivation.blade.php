<div class="w-full sm:col-span-2">
    <label for="cofinancing"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Please explain your reasoning(max. 500 characters).") }}
        <button id="cofinancing-button" data-modal-toggle="cofinancing-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    <textarea id="other_cofinancing" rows="2" name="other_cofinancing"
              class="@error('other_cofinancing') border-red-500 @enderror font-mono block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                                  focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="{{__("Estimated your budget requirements and provide a brief justification.")}}">{{ old('other_cofinancing') ? old('other_cofinancing'): $proposal->pp['other_cofinancing'] ??  '' }}</textarea>
    <p class="mt-1 text-xs">
        <span id="other-cofinancing-count" class="text-gray-500 dark:text-gray-300">0</span>/500
    </p>
    @error('other_cofinancing')
    <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.scrollIntoView({behavior:'smooth', block:'center'})">
        {{ __("This is a required input") }}
    </p>
    @enderror
</div>
<script>
    (function () {
        const ta = document.getElementById('other_cofinancing');
        const c  = document.getElementById('other-cofinancing-count');
        if (!ta || !c) return;

        const max = 500;

        function update() {
            const len = ta.value.length;
            c.textContent = len;

            c.classList.toggle('text-red-600', len > max);
            c.classList.toggle('text-gray-500', len <= max);
            c.classList.toggle('dark:text-red-400', len > max);
            c.classList.toggle('dark:text-gray-300', len <= max);
        }

        ta.addEventListener('input', update);
        update();
    })();
</script>

