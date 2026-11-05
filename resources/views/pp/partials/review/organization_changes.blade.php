@php
    $organizationOriginal = \Illuminate\Support\Arr::only($proposal->pp, ['funding_organization', 'program', 'dsvcoordinating', 'other_coordination', 'eu', 'eu_wallenberg']);
@endphp
<style>
    #project-organization > div {
        padding: .75rem;
        border-radius: .75rem;
        transition: background-color 150ms ease, box-shadow 150ms ease;
    }
    #project-organization .organization-modified {
        background-color: #fffbeb;
        box-shadow: inset 3px 0 0 #f59e0b, inset 0 0 0 1px #fde68a;
    }
    .dark #project-organization .organization-modified {
        background-color: rgb(120 53 15 / 18%);
        box-shadow: inset 3px 0 0 #d97706, inset 0 0 0 1px rgb(217 119 6 / 35%);
    }
    @media (prefers-reduced-motion: reduce) {
        #project-organization > div { transition: none; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const section = document.getElementById('project-organization');
        const original = @json($organizationOriginal);
        const names = ['funding_organization', 'program', 'dsvcoordinating', 'other_coordination', 'eu', 'eu_wallenberg'];
        const selectedValues = {};
        const values = () => {
            const result = {};
            section.querySelectorAll('input[name]').forEach(input => {
                if (names.includes(input.name) && (input.type !== 'radio' || input.checked)) {
                    result[input.name] = input.value;
                }
            });
            return { ...result, ...selectedValues };
        };
        const highlight = () => {
            const current = values();
            Array.from(section.children).forEach(field => {
                const changed = Array.from(field.querySelectorAll('input[name]')).some(input =>
                    names.includes(input.name) && String(current[input.name] ?? '') !== String(original[input.name] ?? '')
                );
                field.classList.toggle('organization-modified', changed);
            });
        };
        let highlightFrame;
        const scheduleHighlight = () => {
            cancelAnimationFrame(highlightFrame);
            highlightFrame = requestAnimationFrame(highlight);
        };
        const updateHighlight = () => {
            highlight();
            // Alpine may update bound values after the native event bubbles.
            scheduleHighlight();
        };
        section.addEventListener('input', updateHighlight);
        section.addEventListener('change', updateHighlight);
        section.addEventListener('proposal-field-change', event => {
            if (!names.includes(event.detail.name)) return;
            selectedValues[event.detail.name] = event.detail.value;
            updateHighlight();
        });
        const registerLivewireHook = () => {
            // Livewire replaces root classes during a morph, including our highlight.
            window.Livewire.hook('morphed', ({ el }) => {
                if (section.contains(el)) scheduleHighlight();
            });
        };
        if (window.Livewire) registerLivewireHook();
        else document.addEventListener('livewire:init', registerLivewireHook, { once: true });
        new MutationObserver(scheduleHighlight).observe(section, {
            subtree: true, childList: true, attributes: true, attributeFilter: ['value', 'checked']
        });
        highlight();
        document.getElementById('proposal-decision-form').addEventListener('submit', function (event) {
            if (!document.getElementById('proposal-form').reportValidity()) {
                event.preventDefault();
                return;
            }
            this.querySelectorAll('[data-organization-change]').forEach(input => input.remove());
            Object.entries(values()).forEach(([name, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                input.dataset.organizationChange = '';
                this.appendChild(input);
            });
        });
    });
</script>
