<!-- Toggle Button -->
<button id="toggleButton"
        class="fixed bottom-2 sm:right-5 z-50 py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white
        hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
    Toggle Review
</button>

<div id="reviewBox"
     class="sm:fixed sm:bottom-24 inset-x-0 px-4 z-40 sm:left-1/2 sm:transform sm:-translate-x-1/2 sm:max-w-2xl w-full bg-white
            dark:bg-gray-900 dark:border-gray-600 p-4 sm:rounded-lg shadow-lg overflow-x-hidden"
>

        {{--}}<div class="sm:hidden sm:col-span-4 my-4">
            <label for="purpose" class="block mb-2 text-sm font-medium text-blue-600 dark:text-white">{{ __("Please Review and Comment") }}</label>
            <textarea id="comment" rows="4"  name="comment_mobile"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-600 focus:ring-primary-500 focus:border-primary-500
                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="Please comment the request">
            </textarea>
        </div>


        <div class="grid h-full max-w-2xl grid-cols-4 bg-white mx-auto font-medium">

            <div class="hidden md:block sm:col-span-4 my-4">
                <label for="purpose" class="block mb-2 text-sm font-medium text-blue-600 dark:text-white">{{ __("Please Review and Comment") }}</label>
                <textarea id="comment" rows="4"  name="comment"
                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-600 focus:ring-primary-500 focus:border-primary-500
                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                          placeholder="Please comment the request">
            </textarea>
            </div>

            <a type="button"
               href="{{ url()->previous() }}"
               class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-blue-500 text-blue-700 font-semibold
                    hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-blue-700 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Cancel")}}</span>
            </a>
            <button type="submit" name="decision" value="return"
                    href=""
                    class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-yellow-400 font-semibold
                    hover:text-white py-2 px-4 border border-yellow-400 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-yellow-400 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Return")}}</span>
            </button>
            <button type="submit" name="decision" value="approve"
                    href=""
                    class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-blue-500 text-blue-700 font-semibold
                    hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="inline text-sm text-red-700 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">
                    <svg class="inline w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 21a9 9 0 1 1 3-17.5m-8 6 4 4L19.3 5M17 14v6m-3-3h6"/>
                    </svg>
                    {{__("Update")}}
                </span>
            </button>
            <button type="submit" name="decision" value="approve"
                    class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-blue-500 text-blue-700 font-semibold
                    hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-blue-700 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Approve")}}</span>
            </button>

        </div>{{--}}
    <form method="POST" action="{{route('fo_review', $dashboard)}}">
        @csrf
        <div class="my-4">
            <label for="comment" class="block mb-2 text-sm font-medium text-blue-600 dark:text-white">
                {{ __("Please Review and Comment") }}
            </label>

            <textarea id="comment" name="comment" rows="4"
                      class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-600 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700
                      dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="Please comment the request"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-between gap-2 w-full">
            <a href="{{ url()->previous() }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-blue-700 font-semibold border border-blue-500 rounded hover:bg-blue-500 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Cancel") }}</span>
            </a>

            <button type="submit" name="decision" value="return"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-yellow-400 font-semibold border border-yellow-400 rounded hover:bg-yellow-400 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Return") }}</span>
            </button>

            <button type="submit" name="decision" value="approve"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-red-600 font-semibold border border-red-600 rounded hover:bg-red-600 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Update") }}</span>
            </button>

            <button type="submit" name="decision" value="approve"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-blue-700 font-semibold border border-blue-500 rounded hover:bg-blue-500 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Approve") }}</span>
            </button>
        </div>
    </form>
</div>
<!-- JS toggle -->
<script>
    document.getElementById('toggleButton').addEventListener('click', function () {
        document.getElementById('reviewBox').classList.toggle('hidden');
    });
</script>
