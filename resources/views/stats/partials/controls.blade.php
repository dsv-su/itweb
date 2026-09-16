<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-2xl dark:text-white">{{ $title }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Submission deadlines in {{ $fromYear }}</p>
    </div>

    <form method="GET" action="{{ url()->current() }}"
          class="flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
        <input type="hidden" name="breakdown" value="{{ request('breakdown', 'overview') }}">
        <label for="stats-year" class="text-sm font-medium text-gray-700 dark:text-gray-200">Year</label>
        <select id="stats-year" name="year" onchange="this.form.requestSubmit()"
                class="rounded-md border-gray-300 bg-white text-sm text-gray-900 focus:border-green-600 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @foreach ($years as $year)
                <option value="{{ $year }}" @selected($year === $fromYear)>{{ $year }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="inline-flex items-center rounded-md border border-green-600 px-3 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:text-green-400">
            Recalculate
        </button>
    </form>
</div>
