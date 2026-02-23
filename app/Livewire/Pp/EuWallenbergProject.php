<?php

namespace App\Livewire\Pp;

use Livewire\Component;

class EuWallenbergProject extends Component
{
    // Tailwind visibility classes
    public string $visibility = 'hidden';
    public string $checkbox = 'block';

    public $proposal;

    // When true, we force "yes" selection and disable radios
    public bool $wallenbergOrg = false;

    // Bound to the radio group
    public ?string $eu_wallenberg = null; // 'yes' | 'no' | null

    protected $listeners = [
        'eu_hide' => 'hideCheckbox',
        'eu_show' => 'showCheckbox',
        'org_wallenberg' => 'wallenberg_org',
        'org_reset' => 'wallenberg_reset',
        'eu_wallenberg_force_no' => 'forceNo',
    ];

    public function mount($proposal = null): void
    {
        $this->proposal = $proposal;

        // Initialize from model safely (pp should be cast to array on the model ideally)
        $this->eu_wallenberg = data_get($proposal, 'pp.eu_wallenberg');

        $this->syncVisibility();
    }

    /**
     * Called automatically whenever $eu_wallenberg changes due to wire:model.
     */
    public function updatedEuWallenberg($value): void
    {
        // Normalize unexpected values
        if (!in_array($value, ['yes', 'no', null], true)) {
            $this->eu_wallenberg = null;
        }

        // If org mode is on, always force yes
        if ($this->wallenbergOrg) {
            $this->eu_wallenberg = 'yes';
        }

        $this->syncVisibility();
    }

    public function forceNo(): void
    {
        // If org mode is on, don't fight it (org mode forces yes)
        if ($this->wallenbergOrg) {
            return;
        }

        $this->eu_wallenberg = 'no';
        $this->syncVisibility(); // same helper you already have
    }

    private function syncVisibility(): void
    {
        $this->visibility = ($this->wallenbergOrg || $this->eu_wallenberg === 'yes')
            ? 'block'
            : 'hidden';
    }

    public function hideCheckbox(): void
    {
        $this->checkbox = 'hidden';
    }

    public function showCheckbox(): void
    {
        $this->checkbox = 'block';
    }

    public function wallenberg_org(): void
    {
        $this->wallenbergOrg = true;
        $this->eu_wallenberg = 'yes';
        $this->syncVisibility();
    }

    public function wallenberg_reset(): void
    {
        $this->wallenbergOrg = false;

        // Return to whatever is stored on the proposal (or null)
        $this->eu_wallenberg = data_get($this->proposal, 'pp.eu_wallenberg');
        $this->syncVisibility();
    }

    public function render()
    {
        return view('livewire.pp.eu-wallenberg-project');
    }
}
