import { initFlowbite, Tooltip } from 'flowbite';
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
            initNavigationDisclosures();
            initDashboardTooltips();
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

// These navigation panels contain ordinary links, not ARIA menus.
function initNavigationDisclosures() {
    document.querySelectorAll('#site-navigation [data-dropdown-toggle]').forEach((trigger) => {
        const id = trigger.getAttribute('data-dropdown-toggle');
        const panel = document.getElementById(id);
        const dropdown = window.FlowbiteInstances?.getInstance('Dropdown', id);
        if (!panel || !dropdown) return;
        const syncExpanded = () => trigger.setAttribute('aria-expanded', String(dropdown.isVisible()));
        dropdown.updateOnShow(syncExpanded);
        dropdown.updateOnHide(syncExpanded);
        syncExpanded();
        if (trigger.dataset.disclosureInitialized) return;
        trigger.dataset.disclosureInitialized = 'true';
        const closeOnEscape = (event) => {
            const current = window.FlowbiteInstances?.getInstance('Dropdown', id);
            if (event.key === 'Escape' && current?.isVisible()) {
                event.preventDefault();
                event.stopPropagation();
                current.hide();
                trigger.focus();
            }
        };
        trigger.addEventListener('keydown', closeOnEscape);
        panel.addEventListener('keydown', closeOnEscape);
    });
}

// Hover/focus content must remain hoverable and dismissible (WCAG 1.4.13).
function initDashboardTooltips() {
    document.querySelectorAll('[data-dashboard-tooltip]').forEach((trigger) => {
        if (trigger.dataset.tooltipInitialized) return;
        const id = trigger.getAttribute('data-dashboard-tooltip');
        const panel = document.getElementById(id);
        if (!panel) return;
        trigger.dataset.tooltipInitialized = 'true';
        trigger.setAttribute('aria-describedby', id);
        const tooltip = new Tooltip(panel, trigger, { triggerType: 'none' });
        let hideTimer;
        let dismissed = false;
        const show = () => {
            clearTimeout(hideTimer);
            dismissed = false;
            if (!tooltip.isVisible()) tooltip.show();
        };
        const scheduleHide = () => {
            clearTimeout(hideTimer);
            hideTimer = setTimeout(() => {
                if (dismissed || (!trigger.matches(':hover, :focus') && !panel.matches(':hover'))) {
                    tooltip.hide();
                }
            }, 150);
        };
        trigger.addEventListener('mouseenter', show);
        trigger.addEventListener('focus', show);
        trigger.addEventListener('mouseleave', scheduleHide);
        trigger.addEventListener('blur', scheduleHide);
        panel.addEventListener('mouseenter', () => clearTimeout(hideTimer));
        panel.addEventListener('mouseleave', scheduleHide);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                dismissed = true;
                clearTimeout(hideTimer);
                tooltip.hide();
            }
        });
    });
}
