<div>
    <div class="p-3 mt-3 flex flex-col">

        <h3 class="text-left mb-2 text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">
            {{__("Teacher information")}}
            @can('access cp')
                <a href="/cp/collections/teachernews" aria-label="Teacher news admin" class="float-right hover:border-blue-600">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.109 17H1v-2a4 4 0 0 1 4-4h.87M10 4.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm7.95 2.55a2 2 0 0 1 0 2.829l-6.364 6.364-3.536.707.707-3.536 6.364-6.364a2 2 0 0 1 2.829 0Z"/>
                    </svg>
                </a>
            @endif
        </h3>

        <label class="inline-flex items-center cursor-pointer">
            <input wire:click="show_status" type="checkbox" value="" class="sr-only peer" @if($status == true) checked @endif>
            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800
                rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4
                after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{__("Room Status")}}</span>
        </label>
    </div>
</div>
