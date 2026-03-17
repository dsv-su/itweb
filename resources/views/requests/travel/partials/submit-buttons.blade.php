<div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ url()->previous() }}"
       class="py-2.5 px-3 w-full sm:w-40 sm:flex-none inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-red-600 bg-white text-red-600 shadow-sm
              hover:bg-red-600 hover:text-white focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none
              dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300
              dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
        {{ __("Cancel") }}
    </a>


    {{--}}<div class="border-t sm:border-t-0 sm:border-s border-gray-200 dark:border-neutral-700"></div>
    <button type="submit"
            name="type"
            value="save"
            class="py-2.5 px-3 w-full sm:w-40 sm:flex-none inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-green-700 bg-white hover:bg-green-800
                   text-green-700 hover:text-white shadow-sm focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none
                   dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300
                   dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
        {{ __("Save") }}
    </button>{{--}}


    <div class="border-t sm:border-t-0 sm:border-s border-gray-200 dark:border-neutral-700"></div>

    <button type="submit"
            class="py-2.5 px-3 w-full sm:flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-700 bg-white hover:bg-blue-800
                   text-blue-700 hover:text-white shadow-sm focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none
                   dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300
                   dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">

        {{ __("Submit proposal") }}

    </button>
</div>
