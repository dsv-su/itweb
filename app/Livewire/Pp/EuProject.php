<?php

namespace App\Livewire\Pp;

use Livewire\Component;

class EuProject extends Component
{
    public string $visibility = 'hidden';
    public string $checkbox = 'block';
    public $proposal;

    // Bound to the radio group
    public ?string $eu = null; // 'yes' | 'no' | null

    protected $listeners = [
        'org_wallenberg' => 'wallenberg_org',
        'org_reset' => 'wallenberg_reset',
    ];

    public function mount($proposal = null): void
    {
        $this->proposal = $proposal;

        // Initialize from model safely
        $this->eu = data_get($proposal, 'pp.eu');

        $this->syncStateFromEu();
    }

    public function updatedEu($value): void
    {
        // Normalize
        if (!in_array($value, ['yes', 'no', null], true)) {
            $this->eu = null;
        }

        $this->syncStateFromEu();
    }

    private function syncStateFromEu(): void
    {
        /*if ($this->eu === 'yes') {
            $this->visibility = 'block';
            $this->dispatch('eu_hide');
        } else {
            // default to "no"/null behaviour
            $this->visibility = 'hidden';
            $this->dispatch('eu_show');
        }*/
        if ($this->eu === 'yes') {
            $this->visibility = 'block';

            // Hide the Wallenberg question UI (your existing behavior)
            $this->dispatch('eu_hide');

            // NEW: force the other component value to "no"
            $this->dispatch('eu_wallenberg_force_no');
        } else {
            $this->visibility = 'hidden';
            $this->dispatch('eu_show');
        }
    }

    public function wallenberg_org(): void
    {
        // Hide the whole EU question when org is Wallenberg
        $this->checkbox = 'hidden';

        // Ensure consistent downstream behavior: show checkbox in the other component
        $this->dispatch('eu_hide');
    }

    public function wallenberg_reset(): void
    {
        $this->checkbox = 'block';

        // Return to stored value (or null) and sync UI + events
        $this->eu = data_get($this->proposal, 'pp.eu');
        $this->syncStateFromEu();
    }

    public function render()
    {
        return view('livewire.pp.eu-project');
    }
}
