import 'flowbite';
import Datepicker from 'flowbite-datepicker/Datepicker'
import Chart from 'chart.js/auto'
import 'chartjs-adapter-moment'

document.addEventListener('DOMContentLoaded', () => {
    initFlowbite();
});


const el = document.getElementById('datepickerId')
if (el) {
  new Datepicker(el, {
    autohide: true,
    format: 'yyyy-mm-dd',
  })
}

// ✅ init flowbite (defer to ensure DOM is settled)
function bootFlowbite() {
    requestAnimationFrame(() => {
        setTimeout(() => {
            initFlowbite()
        }, 0)
    })
}

// run once on load
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bootFlowbite)
} else {
  bootFlowbite()
}

// ✅ re-init after Livewire navigation (v3)
document.addEventListener('livewire:navigated', bootFlowbite)

// ✅ re-init after Livewire DOM morphs (v2 hook)
document.addEventListener('livewire:load', () => {
  bootFlowbite()

  if (window.Livewire?.hook) {
    window.Livewire.hook('message.processed', () => {
      bootFlowbite()
    })
  }
})

window.Chart = Chart

// ... existing code ...

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


