<nav aria-label="Statistics breakdown" class="mt-4 flex flex-wrap gap-2">
    @foreach (['overview' => 'Overview', 'unit' => 'Per unit', 'research_area' => 'Per Research Subject'] as $key => $label)
        <a href="{{ route(request()->route()->getName(), ['year' => $fromYear, 'breakdown' => $key]) }}"
           @if (request('breakdown', 'overview') === $key) aria-current="page" @endif
           class="rounded-md px-4 py-2 text-sm font-medium {{ request('breakdown', 'overview') === $key ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
            {{ $label }}
        </a>
    @endforeach
</nav>
