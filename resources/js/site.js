import 'flowbite'
import { initFlowbite } from 'flowbite'

import Datepicker from "flowbite-datepicker/Datepicker";

const el = document.getElementById("datepickerId");
if (el) new Datepicker(el, {
    autohide: true,
    format: "yyyy-mm-dd",
});

// ✅ init flowbite once
function bootFlowbite() {
    initFlowbite();
}

// run once on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootFlowbite)
} else {
    bootFlowbite()
}

// ✅ re-init after Livewire navigation
document.addEventListener('livewire:navigated', bootFlowbite)

// ✅ re-init after Livewire DOM morphs (components updating)
document.addEventListener('livewire:load', () => {
    bootFlowbite()

    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', () => {
            bootFlowbite()
        })
    }
})

/* ------- your existing theme code can stay ------- */

import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';
window.Chart = Chart;

function syncThemeIcons() {
    const isDark = document.documentElement.classList.contains('dark')

    document.querySelectorAll('.theme-toggle').forEach((btn) => {
        const darkIcon =
            btn.querySelector('#theme-toggle-dark-icon') ||
            btn.querySelector('[data-toggle-icon="moon"]')

        const lightIcon =
            btn.querySelector('#theme-toggle-light-icon') ||
            btn.querySelector('[data-toggle-icon="sun"]')

        if (!darkIcon || !lightIcon) return

        darkIcon.classList.toggle('hidden', isDark)
        lightIcon.classList.toggle('hidden', !isDark)
    })
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.theme-toggle')
    if (!btn) return

    const nowDark = document.documentElement.classList.toggle('dark')
    localStorage.setItem('color-theme', nowDark ? 'dark' : 'light')
    syncThemeIcons()
})

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncThemeIcons)
} else {
    syncThemeIcons()
}

document.addEventListener('livewire:navigated', syncThemeIcons)
document.addEventListener('livewire:load', () => {
    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', () => syncThemeIcons())
    }
})
