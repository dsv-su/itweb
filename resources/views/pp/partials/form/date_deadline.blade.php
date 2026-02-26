<div class="flex flex-col w-full">
    <label for="submission" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
        {{ __("Submission deadline") }}<span class="text-red-600"> *</span>
        <button id="submission-button" data-modal-toggle="submission-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>
    @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete', 'resume']))
        <div class="flex flex-col sm:flex-row items-center w-full">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-blue-700 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                @error('submission')
                <p class="mt-3 text-sm leading-6 text-red-600">{{__("This is a required input")}}</p>
                @enderror
                <input id="submission_deadline"
                       name="submission_deadline"
                       datepicker
                       datepicker-autohide
                       {{--}}datepicker-format="dd/mm/yyyy"{{--}}
                       datepicker-format="yyyy-mm-dd"
                       @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete', 'resume']))
                       value="{{ $proposal['pp']['submission_deadline'] ?? ''}}"
                       @endif
                       {{--}}id="startInput"{{--}}
                       type="text"
                       class="@if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                              @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__("Select date")}}"
                       required>
            </div>
        </div>
    @else
        @include('pp.partials.review.submission')
    @endif
</div>
<script>
    function parseYYYYMMDD(value) {
        // expects "yyyy-mm-dd"
        if (!value) return null;
        const parts = value.split('-');
        if (parts.length !== 3) return null;

        const yyyy = Number(parts[0]);
        const mm = Number(parts[1]);
        const dd = Number(parts[2]);
        if (!dd || !mm || !yyyy) return null;

        const d = new Date(yyyy, mm - 1, dd);
        if (isNaN(d.getTime())) return null;

        // strict check (prevents 2026-02-31 becoming a valid date)
        if (d.getFullYear() !== yyyy || d.getMonth() !== (mm - 1) || d.getDate() !== dd) return null;

        return d;
    }

    function validateDates() {
        const startInput = document.getElementById('start_date');
        const subInput = document.getElementById('submission_deadline');
        const warning = document.getElementById('start_date_warning');

        if (!startInput || !subInput || !warning) return;

        const startDate = parseYYYYMMDD(startInput.value);
        const subDate = parseYYYYMMDD(subInput.value);

        // Only validate when both dates exist
        if (!startDate || !subDate) {
            startInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            startInput.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            warning.classList.add('hidden');
            return;
        }

        const invalid = startDate < subDate;

        if (invalid) {
            startInput.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            startInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            warning.classList.remove('hidden');
        } else {
            startInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            startInput.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            warning.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('start_date');
        const subInput = document.getElementById('submission_deadline');

        if (!startInput || !subInput) return;

        startInput.addEventListener('input', validateDates);
        subInput.addEventListener('input', validateDates);

        startInput.addEventListener('change', validateDates);
        subInput.addEventListener('change', validateDates);

        // Flowbite datepicker event (important!)
        startInput.addEventListener('changeDate', validateDates);
        subInput.addEventListener('changeDate', validateDates);

        validateDates();
    });
</script>

