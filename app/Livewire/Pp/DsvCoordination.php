<?php

namespace App\Livewire\Pp;

use App\Models\ProjectProposal;
use Livewire\Component;

class DsvCoordination extends Component
{
    public $visibility = 'hidden';
    public $proposal;

    public function mount($proposal = null)
    {
        $this->proposal = $proposal;
        if($this->proposal) {
            $this->check();
        }
    }

    public function check()
    {
        if($this->proposal->pp['dsvcoordinating']  == 'no') {
            $this->no();
        }
    }

    public function no()
    {
        $this->visibility = 'block';
    }

    public function yes()
    {
        $this->visibility = 'hidden';
    }

    public function render()
    {
        return view('livewire.pp.dsv-coordination');
    }
}
