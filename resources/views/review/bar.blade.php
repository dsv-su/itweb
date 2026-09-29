<div class="relative w-[calc(100%-3rem)] sm:fixed sm:bottom-24 sm:left-1/2 sm:-translate-x-1/2 sm:max-w-2xl z-40">
    <button type="button" id="toggleButton" aria-controls="reviewBox" aria-expanded="true"
            class="absolute left-full top-1/2 -translate-y-1/2 z-10 inline-flex min-h-36 w-11 items-center justify-center rounded-r-xl border border-l-0 border-blue-700 bg-blue-600 py-4 text-sm font-semibold tracking-wide text-white shadow-lg transition-colors hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 dark:border-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600">
        <span class="whitespace-nowrap [writing-mode:vertical-rl]">Toggle Review</span>
    </button>

    <div id="reviewBox"
         class="w-full bg-white dark:bg-gray-900 dark:border-gray-600 p-4 sm:rounded-lg shadow-lg overflow-x-hidden">

        <form method="POST" action="{{route('review', $dashboard)}}">
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

                <button type="submit" name="decision" value="deny"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-red-600 font-semibold border border-red-600 rounded hover:bg-red-600 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                    <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Deny") }}</span>
                </button>

                <button type="submit" name="decision" value="return"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-yellow-400 font-semibold border border-yellow-400 rounded hover:bg-yellow-400 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                    <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Return") }}</span>
                </button>

                <button type="submit" name="decision" value="approve"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-blue-700 font-semibold border border-blue-500 rounded hover:bg-blue-500 hover:text-white dark:hover:bg-gray-800 dark:border-gray-600 group">
                    <span class="text-sm dark:text-gray-400 group-hover:text-white">{{ __("Approve") }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS toggle -->
<script>
    document.getElementById('toggleButton').addEventListener('click', function () {
        const reviewBox = document.getElementById('reviewBox');
        const isCollapsed = reviewBox.classList.toggle('invisible');

        reviewBox.inert = isCollapsed;
        this.setAttribute('aria-expanded', String(!isCollapsed));
    });
</script>
