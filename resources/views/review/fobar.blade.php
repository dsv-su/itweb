<div class="fixed bottom-20 left-0 z-50 w-full bg-white dark:bg-gray-900 dark:border-gray-600">

        <div class="sm:hidden sm:col-span-4 my-4">
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
            <button type="submit" name="decicion" value="return"
                    href=""
                    class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-yellow-400 font-semibold
                    hover:text-white py-2 px-4 border border-yellow-400 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-yellow-400 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Return")}}</span>
            </button>
            <button type="submit" name="decicion" value="approve"
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
            <button type="submit" name="decicion" value="approve"
                    class="inline-flex flex-col mx-2 items-center justify-center px-5 hover:bg-blue-500 text-blue-700 font-semibold
                    hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded dark:hover:bg-gray-800 group dark:border-gray-600">
                <span class="text-sm text-blue-700 dark:text-gray-400 group-hover:text-white dark:group-hover:text-blue-500">{{__("Approve")}}</span>
            </button>
        </div>
    </form>
</div>
