<div class="w-full">
    <label for="cofinancing_needed" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __("Amount of Cofinancing needed (SEK)") }}
        <span class="text-red-600"> *</span>
        <button id="cofinancing_needed-button" data-modal-toggle="cofinancing_needed-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    @php
        //Review & FO Approval context?
        $isReviewFOApproval = ($type === 'review' && $role === 'FO Approval');

        //Editable
        $isRequired = in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume'])
                      || $isReviewFOApproval;

        // Border color based on context
        $borderClass = $isReviewFOApproval
            ? 'border-blue-600'
            : 'border-gray-300';

        //Background color
        $bgClass = $isReviewFOApproval
            ? 'bg-sky-100 bg-opacity-40'
            : 'bg-gray-50';
    @endphp

    <div class="flex rounded-lg">
        <input
            type="text"
            inputmode="numeric"
            autocomplete="off"
            id="cofinancing_needed_display"
            value="{{ old('cofinancing_needed', $proposal->pp['cofinancing_needed'] ?? '') }}"
            placeholder="CO financing needed"
            @class([
                'font-mono border text-gray-900 text-sm rounded-lg
                 focus:ring-primary-600 focus:border-primary-600
                 block w-full p-2.5
                 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200
                 dark:focus:ring-primary-500 dark:focus:border-primary-500',
                $borderClass,
                $bgClass,
            ])
            @required($isRequired)
            @readonly(!$isRequired)
        >
        <!-- What gets submitted -->
        <input
            type="hidden"
            name="cofinancing_needed"
            id="cofinancing_needed"
            value="{{ old('cofinancing_needed', $proposal->pp['cofinancing_needed'] ?? '') }}"
        >
        @if($isReviewFOApproval)
            <button type="submit"
                    data-tooltip-target="update-tooltip"
                    class="ml-1 text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium
                            rounded-lg text-sm p-1 text-center inline-flex items-center me-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>
                </svg>
                <span class="sr-only">Update</span>
            </button>
        @endif
    </div>

</div>
<script>
    (function () {
        const display = document.getElementById('cofinancing_needed_display');
        const hidden  = document.getElementById('cofinancing_needed');

        if (!display || !hidden) return;

        const toDigits = (v) => (v ?? '').toString().replace(/[^\d]/g, ''); // keep digits only

        const format = (digits) => {
            if (!digits) return '';
            return new Intl.NumberFormat('sv-SE', { maximumFractionDigits: 0 }).format(Number(digits));
        };

        // Initialize (format whatever is in the value)
        const initDigits = toDigits(display.value);
        hidden.value = initDigits;
        display.value = format(initDigits);

        display.addEventListener('input', () => {
            const start = display.selectionStart;

            const digits = toDigits(display.value);
            hidden.value = digits;

            const formatted = format(digits);
            display.value = formatted;

            // Try to keep cursor position reasonably stable
            const diff = formatted.length - (display.value.length);
            const newPos = Math.max(0, (start ?? formatted.length) + diff);
            display.setSelectionRange(newPos, newPos);
        });

        // Safety: strip formatting on form submit (hidden already clean, but keeps things consistent)
        display.closest('form')?.addEventListener('submit', () => {
            hidden.value = toDigits(display.value);
        });
    })();
</script>
