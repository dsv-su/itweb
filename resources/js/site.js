import 'flowbite';
import Datepicker from "flowbite-datepicker/Datepicker";
const el = document.getElementById("datepickerId");
if (el) new Datepicker(el, {
    autohide: true,
    format: "yyyy-mm-dd",
});

import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';
window.Chart = Chart;
function syncThemeIcons() {
    const btn = document.getElementById('theme-toggle')
    if (!btn) return

    const darkIcon = document.getElementById('theme-toggle-dark-icon')
    const lightIcon = document.getElementById('theme-toggle-light-icon')
    if (!darkIcon || !lightIcon) return

    const isDark = document.documentElement.classList.contains('dark')
    darkIcon.classList.toggle('hidden', isDark)
    lightIcon.classList.toggle('hidden', !isDark)
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('#theme-toggle')
    if (!btn) return

    const nowDark = document.documentElement.classList.toggle('dark')
    localStorage.setItem('color-theme', nowDark ? 'dark' : 'light')
    syncThemeIcons()
})

// run once on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncThemeIcons)
} else {
    syncThemeIcons()
}

// if Livewire is used, re-sync after updates/navigation
document.addEventListener('livewire:navigated', syncThemeIcons)
document.addEventListener('livewire:load', () => {
    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', () => syncThemeIcons())
    }
})

