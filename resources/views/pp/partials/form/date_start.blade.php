<div class="flex flex-col w-full">
    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
        {{ __("Start date expected") }}<span class="text-red-600"> *</span>
        <button id="start_date-button" data-modal-toggle="start_date-modal" class="inline" type="button">
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

                <input id="start_date"
                       name="start_date"
                       datepicker
                       datepicker-autohide
                       {{--}}datepicker-format="dd/mm/yyyy"{{--}}
                       datepicker-format="yyyy-mm-dd"
                       @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete', 'resume']))
                       value="{{ $proposal['pp']['start_date'] ?? ''}}"
                       @endif
                       {{--}}id="endInput"{{--}}
                       type="text"
                       class="@if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                  @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif
                                                   dark:border-gray-600 dark:placeholder-gray-400 dark:placeholder:text-gray-200 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__("Select date")}}"
                       required>
                <p id="start_date_warning" class="mt-2 text-sm text-red-600 hidden">
                    {{ __("Start date cannot be before the submission deadline.") }}
                </p>
                @error('start_date')
                <p class="mt-3 text-sm leading-6 text-red-600">{{__("This is a required input")}}</p>
                @enderror
            </div>

        </div>
    @else
        @include('pp.partials.review.start_date')
    @endif
</div>
