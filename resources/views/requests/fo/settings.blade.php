@extends('layouts.app')
@section('content')
@include('dsvheader')
@include('navbar.navbar')
<section lang="sv" id="fo-settings" class="bg-gray-50 px-4 py-8 sm:px-6 sm:py-12 dark:bg-gray-900" aria-labelledby="settings-title">
    <div class="mx-auto max-w-5xl">
        <header class="mb-8">
            <p class="mb-2 text-sm font-semibold text-blue-700 dark:text-blue-300">Ekonomi</p>
            <h1 id="settings-title" class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Ekonomiinställningar</h1>
            <p class="mt-3 text-base text-gray-600 dark:text-gray-400">Hantera attest- och aviseringsmottagare, projekt och PDF-inställningar.</p>
        </header>

        @if(session('status'))
            <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <nav class="overflow-x-auto border-b border-gray-200 bg-gray-50/80 px-4 pt-2 sm:px-6 dark:border-gray-700 dark:bg-gray-800" aria-label="Avsnitt för ekonomiinställningar">
                <div class="flex min-w-max gap-2" data-settings-tabs>
                    @foreach(['notifications' => 'Aviseringar', 'projects' => 'Projekt', 'pdf-language' => 'PDF-språk'] as $tab => $label)
                        <a id="settings-tab-{{ $tab }}" href="#settings-{{ $tab }}" class="border-b-2 border-transparent px-4 py-4 text-sm font-semibold text-gray-600 transition-colors hover:text-blue-700 aria-selected:border-blue-700 aria-selected:text-blue-700 dark:text-gray-400 dark:hover:text-blue-300 dark:aria-selected:border-blue-300 dark:aria-selected:text-blue-300">{{ $label }}</a>
                    @endforeach
                </div>
            </nav>

            <div class="p-5 sm:p-8">
                <section id="settings-notifications" aria-labelledby="settings-tab-notifications" data-settings-panel>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Attest- och Aviseringsmottagare</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-400">Välj vilka ekonomihandläggare som ska attestera och få aviseringar. Klicka på Spara ändringar för att spara ditt val.</p>
                    @include('requests.vice.partials.fo')
                </section>

                <section id="settings-projects" class="mt-8" aria-labelledby="settings-tab-projects" data-settings-panel>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Projekthantering</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-400">Hantera ekonomiprojekt och importera projektdata från Excel.</p>
                    <div class="mt-6 rounded-xl border border-gray-200 p-6 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Projekt och Excel-import</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">Öppna projektadministrationen för att granska projekt eller ladda upp en Excel-fil.</p>
                        <a href="{{ route('fo.projects') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                            Hantera projekt
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                        </a>
                    </div>
                </section>

                <section id="settings-pdf-language" class="mt-8" aria-labelledby="settings-tab-pdf-language" data-settings-panel>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">PDF-språk</h2>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">Inte tillgängligt ännu</span>
                    </div>
                    <p id="pdf-language-help" class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-400">Det går inte att ändra språk för PDF-utskrifter ännu. Svenska är förvalt.</p>
                    <fieldset disabled aria-describedby="pdf-language-help" class="mt-6">
                        <legend class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Språk för PDF-utskrifter</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach(['sv' => 'Svenska', 'en' => 'Engelska'] as $value => $label)
                                <label for="pdf-language-{{ $value }}" class="flex cursor-not-allowed items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/30 dark:text-gray-400">
                                    <input id="pdf-language-{{ $value }}" type="radio" name="pdf_language" value="{{ $value }}" @checked($value === 'sv') class="h-4 w-4 border-gray-300 text-blue-700 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (() => {
        const root = document.getElementById('fo-settings');
        const tablist = root.querySelector('[data-settings-tabs]');
        const tabs = [...tablist.querySelectorAll('a')];
        const panels = [...root.querySelectorAll('[data-settings-panel]')];
        tablist.setAttribute('role', 'tablist');
        tablist.setAttribute('aria-label', 'Ekonomiinställningar');

        const activate = (tab) => {
            tabs.forEach((item, index) => {
                const selected = item === tab;
                item.setAttribute('aria-selected', String(selected));
                item.tabIndex = selected ? 0 : -1;
                panels[index].hidden = !selected;
            });
        };

        tabs.forEach((tab, index) => {
            tab.setAttribute('role', 'tab');
            tab.setAttribute('aria-controls', panels[index].id);
            panels[index].setAttribute('role', 'tabpanel');
            panels[index].classList.remove('mt-8');
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                activate(tab);
                history.replaceState(null, '', tab.hash);
            });
            tab.addEventListener('keydown', (event) => {
                let next;
                if (event.key === 'ArrowRight') next = (index + 1) % tabs.length;
                if (event.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
                if (event.key === 'Home') next = 0;
                if (event.key === 'End') next = tabs.length - 1;
                if (next === undefined) return;
                event.preventDefault();
                tabs[next].click();
                tabs[next].focus();
            });
        });

        const fromHash = () => tabs.find((tab) => tab.hash === window.location.hash) || tabs[0];
        activate(@json($errors->any() || session()->has('status')) ? tabs[0] : fromHash());
        window.addEventListener('hashchange', () => activate(fromHash()));
    })();
</script>
@endpush
