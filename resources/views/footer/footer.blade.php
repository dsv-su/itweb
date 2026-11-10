<footer
    class="site-footer"
    aria-label="{{ __('Site footer') }}"
>
    <div class="site-footer__inner">
        <div class="site-footer__panel">
            <div class="site-footer__brand">

                <!-- Light mode logo  -->
                <img
                    class="site-footer__logo block dark:hidden"
                    src="{{ asset('images/su_logo.png') }}"
                    alt="Stockholms universitet"
                />

                <!-- Dark mode logo -->
                <img
                    class="site-footer__logo hidden dark:block"
                    src="{{ asset('images/su_logo_dark.png') }}"
                    alt="Stockholms universitet"
                />

                <p class="site-footer__description">
                    {{ __("Department of Computer and Systems Sciences") }}
                </p>

            </div>

            <section
                class="site-footer__group"
                aria-labelledby="footer-dsv-it-systems"
            >
                <h2
                    id="footer-dsv-it-systems"
                    class="site-footer__heading"
                >
                    {{ __("DSV IT Systems") }}
                </h2>

                <div class="site-footer__links site-footer__links--dsv">
                    <a
                        class="site-footer__link"
                        href="https://daisy.dsv.su.se"
                    >
                        <span>Daisy</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://forum.dsv.su.se"
                    >
                        <span>Forum</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://nextilearn.dsv.su.se"
                    >
                        <span>NextILearn</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://otrs.dsv.su.se"
                    >
                        <span>OTRS</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://play.dsv.su.se"
                    >
                        <span>Play</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://it.dsv.su.se/projectproposals"
                    >
                        <span>Project Proposals</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://scipro.dsv.su.se"
                    >
                        <span>SciPro Project</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://handledning.dsv.su.se"
                    >
                        <span>Handledning</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://thesisinfo.dsv.su.se"
                    >
                        <span>Thesis Info</span>
                    </a>
                </div>
            </section>

            <section
                class="site-footer__group"
                aria-labelledby="footer-su-it-systems"
            >
                <h2
                    id="footer-su-it-systems"
                    class="site-footer__heading"
                >
                    {{ __("SU IT Systems") }}
                </h2>

                <div class="site-footer__links">
                    <a
                        class="site-footer__link"
                        href="https://pp-prod-admin.it.su.se/polopoly/"
                    >
                        <span>{{ __("Your Polopoly profile page") }}</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://hr.su.se"
                    >
                        <span>Primula, HR system</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://rdprodsu.rdsaas.se/rp/SSO/Saml"
                    >
                        <span>Raindance</span>
                    </a>

                    <a
                        class="site-footer__link"
                        href="https://ebox.su.se/"
                    >
                        <span>Webmail ebox</span>
                    </a>
                    <a
                        class="site-footer__link"
                        href="https://su.se/box"
                    >
                        <span>Box</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</footer>
