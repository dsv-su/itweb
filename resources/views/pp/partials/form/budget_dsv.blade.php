<div class="w-full">
    <label for="budget_dsv" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Budget for DSV") }}<span class="text-red-600"> *</span>
        <button id="budget_dsv-button" data-modal-toggle="budget_dsv-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>

    @php
        //Review & FO Approval
        $isReviewFOApproval = ($type === 'review' && $role === 'FO Approval');

        //Editable
        $isRequired = in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume'])
                      || $isReviewFOApproval;

        //Border color based on context
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
            id="budget_dsv_display"
            value="{{ old('budget_dsv', $proposal->pp['budget_dsv'] ?? '') }}"
            placeholder="DSV budget"
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
        <!-- Hidden clean value to submit -->
        <input
            type="hidden"
            name="budget_dsv"
            id="budget_dsv"
            value="{{ old('budget_dsv', $proposal->pp['budget_dsv'] ?? '') }}"
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
{{--}}@push('scripts'){{--}}
    <script>
        (function () {
            const display = document.getElementById('budget_dsv_display');
            const hidden  = document.getElementById('budget_dsv');
            if (!display || !hidden) return;

            const toDigits = (v) => (v ?? '').toString().replace(/[^\d]/g, '');

            const format = (digits) => {
                if (!digits) return '';
                return new Intl.NumberFormat('sv-SE', { maximumFractionDigits: 0 })
                    .format(Number(digits));
            };

            // init
            const initDigits = toDigits(display.value);
            hidden.value = initDigits;
            display.value = format(initDigits);

            display.addEventListener('input', () => {
                const digits = toDigits(display.value);
                hidden.value = digits;
                display.value = format(digits);
            });

            display.closest('form')?.addEventListener('submit', () => {
                hidden.value = toDigits(display.value);
            });
        })();
    </script>
{{--}}@endpush{{--}}
