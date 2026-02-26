<!-- Toggle Button -->
<button id="toggleButton"
        class="fixed bottom-2 sm:right-5 z-50 py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white
        hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
    Toggle Review
</button>

<!-- Review Box -->
<div id="reviewBox"
     class="sm:fixed sm:bottom-24 inset-x-0 px-4 z-40 sm:left-1/2 sm:transform sm:-translate-x-1/2 sm:max-w-2xl w-full bg-white
            dark:bg-gray-900 dark:border-gray-600 p-4 sm:rounded-lg shadow-lg overflow-x-hidden">

    <form method="POST" action="{{ route('pp.decision') }}">
        @csrf

        <div class="my-4">
            <label for="comment" class="block mb-2 text-sm font-medium text-blue-600 dark:text-white">
                {{ __("Please Review and Comment") }}
            </label>
            <!-- Lock overview for UnitHeads -->
            @if(in_array($dashboard->state, ['head_approved', 'fo_approved']))
                @include('pp.partials.review.review_overview')
            @endif

            <textarea id="comment" name="comment" rows="4"
                      class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-600 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700
                      dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="Please comment the request"></textarea>

            <input type="hidden" name="id" value="{{ $proposal->id }}">
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

<!-- JS toggle -->
<script>
    document.getElementById('toggleButton').addEventListener('click', function () {
        document.getElementById('reviewBox').classList.toggle('hidden');
    });
</script>



