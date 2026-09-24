<?php

declare(strict_types=1);

namespace App\Livewire\Select2;

use Livewire\Component;
use App\Models\Project;

/**
 * Single Select2 Component
 *
 * @package Blockpc\Select2Wire
 */
class ProjectSelect2 extends Component
{
    public Project $Project;
    public $search;
    public bool $showProjectWarning = false;
    public bool $showUpdateButton = false;
    public string $updateFormId = 'fo-review-form';

    protected $listeners = [
        'set-Project' => 'set_Project',
        'clear'
    ];

    public function mount($id = 0, bool $showProjectWarning = false, bool $showUpdateButton = false, string $updateFormId = 'fo-review-form')
    {
        $this->showProjectWarning = $showProjectWarning;
        $this->showUpdateButton = $showUpdateButton;
        $this->updateFormId = $updateFormId;

        if($id != 0) {
            $this->Project = Project::where('project', $id)->first() ?? new Project;
        } else {
            $this->Project = new Project;
        }
    }

    public function getOptionsProperty()
    {
        return Project::where('project', 'LIKE', "%{$this->search}%")->orWhere('description', 'LIKE', "%{$this->search}%")->get();
    }

    public function render()
    {
        return view('livewire.select2.Project-select2', [
            'options' => $this->options,
        ]);
    }

    public function save()
    {
        $proj = Project::where('project', $this->search)->first();
        if(!empty($proj)) {
            $this->Project = $proj;
        }
        $this->search = "";
    }

    public function select(Project $Project)
    {
        $this->Project = $Project;
    }

    /** listener */
    public function clear()
    {
        $this->Project = new Project;
        $this->reset('search');
    }

    /** listener */
    public function set_Project(Project $Project)
    {
        $this->Project = $Project;
    }
}
