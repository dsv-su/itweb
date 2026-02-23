<footer
    class="w-full py-4 px-4 sm:px-6 lg:px-8 border-y border-susecondary overflow-x-hidden"
    aria-label="Site footer"
>
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 lg:grid-cols-5 gap-8 mb-2">
            <div class="col-span-full lg:col-span-1">

                <!-- Light mode logo  -->
                <img
                    class="h-24 block dark:hidden"
                    src="{{ asset('images/su_logo.png') }}"
                    alt="Stockholms universitet"
                />

                <!-- Dark mode logo -->
                <img
                    class="h-24 hidden dark:block"
                    src="{{ asset('images/su_logo_dark.png') }}"
                    alt=""
                    aria-hidden="true"
                />

                <p class="mt-3 dark:ml-3 text-sm text-gray-700 dark:text-gray-200">
                    {{ __("Department of Computer and Systems Sciences") }}
                </p>

            </div>
        </div>
    </div>
</footer>
