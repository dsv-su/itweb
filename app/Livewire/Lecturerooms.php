<?php

namespace App\Livewire;

use Livewire\Component;

class Lecturerooms extends Component
{
    public $status;

    public function mount()
    {
        $this->status = false;
    }

    public function show_status()
    {
        $this->status = !$this->status;
        $this->dispatch('contentChanged', lecturerooms: $this->status);
    }

    public function render()
    {
        return view('livewire.lecturerooms');
    }
}
