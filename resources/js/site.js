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

// Flowbite handles opening/Escape; keep focus inside travel help dialogs and
// return it to the invoking control when the dialog closes.
function initTravelHelpFocus() {
    document.querySelectorAll('[data-travel-help]').forEach((dialog) => {
        if (dialog.dataset.focusInitialized) return;
        dialog.dataset.focusInitialized = 'true';
        let opener = null;
        let wasOpen = false;
        const focusable = () => [...dialog.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex="0"]'
        )].filter((element) => element.getClientRects().length);
        new MutationObserver(() => {
            const isOpen = !dialog.classList.contains('hidden');
            if (isOpen === wasOpen) return;
            wasOpen = isOpen;
            if (isOpen) {
                opener = document.activeElement;
                (focusable()[0] ?? dialog).focus();
            } else if (opener?.isConnected) {
                opener.focus();
            }
        }).observe(dialog, { attributes: true, attributeFilter: ['class'] });
        dialog.addEventListener('keydown', (event) => {
            if (event.key !== 'Tab') return;
            const elements = focusable();
            const first = elements[0];
            const last = elements.at(-1);
            if (!first) {
                event.preventDefault();
                dialog.focus();
            } else if (event.shiftKey && (document.activeElement === first || document.activeElement === dialog)) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
        document.addEventListener('focusin', (event) => {
            if (wasOpen && !dialog.contains(event.target)) {
                (focusable()[0] ?? dialog).focus();
            }
        });
    });
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTravelHelpFocus);
} else {
    initTravelHelpFocus();
}
document.addEventListener('livewire:navigated', initTravelHelpFocus);
