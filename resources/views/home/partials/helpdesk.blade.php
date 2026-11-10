<div class="relative flex flex-col items-start justify-center rounded-xl border border-susecondary p-6 text-center md:order-1 dark:border-gray-800">
    <div class="mt-0 text-left">
        <h3 class="text-lg font-semibold text-gray-800 md:text-xl dark:text-gray-200">
            DSV Helpdesk
        </h3>
        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-300">
            {{ __('Phone') }}: 08-16 1648
        </p>
        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-300">
            {{ __('Email') }}:
            <a href="mailto:helpdesk@dsv.su.se" aria-label="Helpdesk email" class="inline-flex items-center gap-x-1.5 font-medium text-blue-800 dark:text-gray-200">
                helpdesk@dsv.su.se
            </a>
        </p>
        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-300">
            SU, tel 08-16 1999
        </p>

        <x-home.localized-link slug="support" class="mt-4 inline-flex items-center gap-x-1.5 font-medium text-blue-800 dark:text-gray-200">
            {{ __('More ways to get help') }}
        </x-home.localized-link>
    </div>
</div>
