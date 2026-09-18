<form method="GET" action="{{ url()->current() }}"
      class="flex flex-wrap items-center gap-3 pb-3 md:ml-auto">
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
    @if ($hasChartData ?? false)
        <button type="submit" name="download" value="xlsx"
                class="inline-flex items-center rounded-md border border-blue-600 px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:text-blue-400">
            Download
        </button>
    @endif
</form>
