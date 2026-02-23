<?php

namespace App\Livewire\Pp;

use Livewire\Component;

class Cofinancing extends Component
{
    public $visibility = false;
    public $proposal;

    public function mount($proposal = null)
    {
        $this->proposal = $proposal;
        $this->check();
    }

    public function check()
    {
        if($this->proposal->pp['cofinancing'] ?? '' == 'yes') {
            $this->cofinancing();
        }
    }

    public function cofinancing()
    {
        $this->visibility = !$this->visibility;
    }

    public function render()
    {
        return view('livewire.pp.cofinancing');
    }
}
