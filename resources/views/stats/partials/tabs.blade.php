<section class="bg-white dark:bg-gray-900">
    <div class="max-w-6xl px-4 py-4 mx-auto">
        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="flex gap-x-2">
                <a href="{{ route('pp.stats.committed') }}"
                   class="{{ request()->routeIs('pp.stats.committed') ? 'text-blue-600 border-b-transparent' : 'text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}
                       -mb-px py-3 px-4 inline-flex items-center gap-2 bg-white text-sm font-medium text-center
                       border border-gray-200 rounded-t-lg dark:bg-neutral-800 dark:border-neutral-700">
                    Commited
                </a>

                <a href="{{ route('pp.stats.approved') }}"
                   class="{{ request()->routeIs('pp.stats.approved') ? 'text-blue-600 border-b-transparent' : 'text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}
                       -mb-px py-3 px-4 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center
                       border border-gray-200 rounded-t-lg dark:bg-neutral-700 dark:border-neutral-700">
                    Granted
                </a>
            </nav>
        </div>
    </div>
</section>


