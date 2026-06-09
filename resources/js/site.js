import { initFlowbite } from 'flowbite';
import Datepicker from 'flowbite-datepicker/Datepicker';
import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';

function initDatepickers() {
    document.querySelectorAll('#datepickerId').forEach((el) => {
        if (el.dataset.datepickerInitialized === 'true') return;

        new Datepicker(el, {
            autohide: true,
            format: 'yyyy-mm-dd',
        });

        el.dataset.datepickerInitialized = 'true';
    });
}

function bootUi() {
    requestAnimationFrame(() => {
        setTimeout(() => {
            initFlowbite();
            initDatepickers();
            syncThemeIcons();
        }, 0);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootUi);
} else {
    bootUi();
}

document.addEventListener('livewire:navigated', bootUi);

document.addEventListener('livewire:load', () => {
    bootUi();

    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', bootUi);
    }
});

window.Chart = Chart;

function syncThemeIcons() {
    const isDark = document.documentElement.classList.contains('dark');

    document.querySelectorAll('.theme-toggle').forEach((btn) => {
        btn.setAttribute('aria-pressed', String(isDark));

        const darkIcon =
            btn.querySelector('#theme-toggle-dark-icon') ||
            btn.querySelector('[data-toggle-icon="moon"]');

        const lightIcon =
            btn.querySelector('#theme-toggle-light-icon') ||
            btn.querySelector('[data-toggle-icon="sun"]');

        if (!darkIcon || !lightIcon) return;

        darkIcon.classList.toggle('hidden', isDark);
        lightIcon.classList.toggle('hidden', !isDark);
    });
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.theme-toggle');
    if (!btn) return;

    const nowDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('color-theme', nowDark ? 'dark' : 'light');
    syncThemeIcons();
});
