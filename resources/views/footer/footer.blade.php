@php
    $footerLinkClasses = 'inline-flex min-h-11 w-full items-center justify-center rounded-md border border-susecondary bg-white px-3 py-2 text-sm font-semibold text-suprimary shadow-sm transition hover:bg-susecondary/30 focus:outline-none focus:ring-2 focus:ring-susecondary focus:ring-offset-2 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-offset-gray-900 sm:min-h-0 sm:w-auto sm:justify-start sm:rounded-none sm:border-0 sm:bg-transparent sm:p-0 sm:font-normal sm:text-gray-700 sm:shadow-none sm:hover:bg-transparent sm:hover:text-sudepartment sm:focus:ring-offset-0 sm:dark:bg-transparent sm:dark:text-gray-200 sm:dark:hover:bg-transparent sm:dark:hover:text-white';
@endphp

<footer
    class="w-full py-4 px-4 sm:px-6 lg:px-8 border-y border-susecondary overflow-x-hidden"
    aria-label="Site footer"
>
    <div class="max-w-7xl mx-auto">
        <div class="grid gap-8 md:grid-cols-4 lg:grid-cols-5 mb-2">
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

            <section
                class="col-span-full lg:col-span-2"
                aria-labelledby="footer-dsv-it-systems"
            >
                <h2
                    id="footer-dsv-it-systems"
                    class="text-base font-bold uppercase tracking-wide text-gray-950 dark:text-gray-100"
                >
                    {{ __("DSV IT Systems") }}
                </h2>

                <div class="mt-4 grid gap-x-12 gap-y-3 text-sm sm:grid-cols-2">
                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://daisy.dsv.su.se"
                    >
                        <span>Daisy</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://forum.dsv.su.se"
                    >
                        <span>Forum</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://nextilearn.dsv.su.se"
                    >
                        <span>NextILearn</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://otrs.dsv.su.se"
                    >
                        <span>OTRS</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://play.dsv.su.se"
                    >
                        <span>Play</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://it.dsv.su.se/projectproposals"
                    >
                        <span>Project Proposals</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://scipro.dsv.su.se"
                    >
                        <span>SciPro Project</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://handledning.dsv.su.se"
                    >
                        <span>Handledning</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://thesisinfo.dsv.su.se"
                    >
                        <span>Thesis Info</span>
                    </a>
                </div>
            </section>

            <section
                class="col-span-full lg:col-span-2"
                aria-labelledby="footer-su-it-systems"
            >
                <h2
                    id="footer-su-it-systems"
                    class="text-base font-bold uppercase tracking-wide text-gray-950 dark:text-gray-100"
                >
                    {{ __("SU IT Systems") }}
                </h2>

                <div class="mt-4 grid gap-x-12 gap-y-3 text-sm sm:grid-cols-2 lg:grid-cols-1">
                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://pp-prod-admin.it.su.se/polopoly/"
                    >
                        <span>{{ __("Your Polopoly profile page") }}</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://hr.su.se"
                    >
                        <span>Primula, HR system</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://rdprodsu.rdsaas.se/rp/SSO/Saml"
                    >
                        <span>Raindance</span>
                    </a>

                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://ebox.su.se/"
                    >
                        <span>Webmail ebox</span>
                    </a>
                    <a
                        class="{{ $footerLinkClasses }}"
                        href="https://su.se/box"
                    >
                        <span>Box</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</footer>
