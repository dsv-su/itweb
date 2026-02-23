<?php

namespace App\Livewire\Pp;

use App\Models\SettingsOh;
use Livewire\Component;

class Ohcost extends Component
{
    public $type;
    public $ohcost;
    public $isEu;
    public $progress = false, $progress_25 = false, $progress_50 = false, $progress_75 = false, $progress_100 = false;
    public $exceed = false;
    public $proposal;
    public $max, $threshold_1, $threshold_2, $threshold_3;

    protected $listeners = [
        'progress-refresh' => '$refresh'
    ];

    public function mount($type, $proposal)
    {
        $this->type = $type;
        $this->proposal = $proposal;
        if($this->proposal) {
            $this->ohcost = $proposal->pp['oh_cost'] ?? 0;
        }
        $oh_settings = SettingsOh::first();
        $this->isEu = ($this->proposal?->pp['eu_wallenberg'] ?? null) === 'yes';
        $this->max = $this->isEu ? $oh_settings->oh_eu : $oh_settings->oh_max;

        $this->threshold();
    }

    public function threshold(): void
    {
        $this->threshold_1 = (int) round($this->max * 0.25);
        $this->threshold_2 = (int) round($this->max * 0.50);
        $this->threshold_3 = (int) round($this->max * 0.75);
    }

    public function updatedOhcost($value = null): void
    {
        $this->resetProgress();
        $this->ohcost = (int) $value;

        //Hide progress when empty
        // if ($value === '' || $value === null) return;

        if ($this->ohcost <= 0) {
            return;
        }

        if ($this->ohcost > $this->max) {
            $this->exceed = true;
            return;
        }

        $this->progress = true;

        if ($this->ohcost <= $this->threshold_1) { $this->progress_25 = true; return; }
        if ($this->ohcost <= $this->threshold_2) { $this->progress_50 = true; return; }
        if ($this->ohcost <= $this->threshold_3) { $this->progress_75 = true; return; }

        $this->progress_100 = true;
    }
    private function resetProgress(): void
    {
        $this->progress = false;
        $this->progress_25 = false;
        $this->progress_50 = false;
        $this->progress_75 = false;
        $this->progress_100 = false;
        $this->exceed = false;
    }
    public function render()
    {
        return view('livewire.pp.ohcost');
    }
}
