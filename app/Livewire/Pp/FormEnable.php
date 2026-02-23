<?php

namespace App\Livewire\Pp;

use Livewire\Component;

class FormEnable extends Component
{
    public $fos, $vicehead, $oh;
    public $form;

    public function mount($fos, $vicehead, $oh)
    {
        $this->fos = $fos;
        $this->vicehead = $vicehead;
        $this->oh = $oh;
        //$this->oh->refresh();
    }

    public function form_enable()
    {
        $this->form = !$this->form;
    }
    public function switchButton()
    {
        $this->oh->form_enable = !$this->oh->form_enable;
        $this->oh->save();
    }

    public function render()
    {
        return view('livewire.pp.form-enable');
    }
}
