<?php

declare(strict_types=1);

namespace App\Livewire\Select2;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectleaderSelect2 extends Component
{
    public User $projectleader;
    public $search;

    protected $listeners = [
        'set-projectleader' => 'set_Projectleader',
        'clearprojectleader'
    ];

    public function mount()
    {
        //Retrive auth user
        $this->projectleader = Auth::user();
    }

    public function getOptionsProperty()
    {
        return User::where('name', 'LIKE', "%{$this->search}%")->get();
    }

    public function render()
    {
        return view('livewire.select2.Projectleader-select2', [
            'options' => $this->options,
        ]);
    }

    public function save()
    {
        $projectleader = User::where('name',$this->search)->first();
        if(!empty($projectleader)) {
            $this->projectleader = $projectleader;
            $this->dispatch('selectedProjectleader', $this->projectleader->id);
        }
        $this->search = "";
    }

    public function select(User $projectleader)
    {
        $this->projectleader = $projectleader;
        $this->dispatch('selectedProjectleader', $this->projectleader->id);
    }

    /** listener */
    public function clearprojectleader()
    {
        $this->projectleader = new User;
        $this->reset('search');
    }

    /** listener */
    public function set_Projectleader(User $projectleader)
    {
        $this->projectleader = $projectleader;
    }
}
