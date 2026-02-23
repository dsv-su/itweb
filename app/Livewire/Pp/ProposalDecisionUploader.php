<?php

namespace App\Livewire\Pp;

use App\Models\Dashboard;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ProposalDecisionUploader extends Component
{
    use WithFileUploads;

    const SENT = 'sent';

    public $proposal;
    public $dashboard;
    public $decisionfiles = [];
    public $savedfiles = [];
    public $stored = false;
    public $directory;
    public $allow;
    public $type;

    protected $listeners = [
        'upload_refresh' => '$refresh'
    ];

    protected function rules(): array
    {
        return [
            'decisionfiles'   => 'array',
            'decisionfiles.*' => 'file|max:20480|mimes:txt,pdf,doc,docx,ppt,pptx,odt,pages,zip,rar,rtf',
        ];
    }
    public function mount($proposal, string $type): void
    {
        $this->proposal  = $proposal;
        $this->type      = $type;
        $this->directory =
            ProposalsDirectory::MAIN.
            $proposal->id.
            ProposalsDirectory::DECISION;

        $this->dashboard = Dashboard::firstWhere('request_id', $proposal->id);

        if ($this->dashboard) {
            $this->allowUpload();
            return;
        }

        $this->allow = true;
    }

    public function reportStageStatus($status)
    {
        $this->proposal->status_stage2 = $status;
        $this->proposal->save();
    }
    public function allowUpload(): void
    {
        $userId = Auth::id(); // avoids loading the full user model

        $allowedUserIds = array_values(array_filter([
            $this->dashboard->user_id,
            $this->dashboard->head_id,
            $this->dashboard->vice_id,
            $this->dashboard->fo_id,
        ])); // removes null/empty values

        if (! in_array($userId, $allowedUserIds, true)) {
            $this->allow = false;
            return;
        }

        $allowedStates = array_merge([
            self::SENT,
        ], []);

        $this->allow = in_array($userId, $allowedUserIds)
            && in_array($this->dashboard->state, $allowedStates);
    }

    public function finishUpload($name, $tmpPath, $isMultiple)
    {
        $this->toggleStored();
        $this->cleanupOldUploads();
        $decisionfiles = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        })->toArray();
        $this->emitSelf('upload:finished', $name, collect($decisionfiles)->map->getFilename()->toArray());

        $decisionfiles = array_merge($this->getPropertyValue($name), $decisionfiles);
        $this->syncInput($name, $decisionfiles);
    }

    public function storefiles()
    {
        $this->validate();
        foreach($this->decisionfiles as $file) {
            $this->savedfiles[$file->getClientOriginalName()] = [
                'path' => $file->store(path: $this->directory),
                'tmp' => basename($file->getRealPath()),
                'size' => round($file->getSize()/1000),
                'date' => now()->format('d/m/Y'),
                'type' => 'decision',
                'review' => 'pending',
                'uploader' => Auth::user()->name
            ];
        }

        //Update files to model
        $this->updateProposal();

        //Toggled buttons
        $this->toggleStored();
        $this->decisionfiles = [];
        $this->dispatch('upload_refresh');
    }

    public function updateProposal()
    {
        $existing = is_array($this->proposal->files) ? $this->proposal->files : [];
        $this->proposal->files = array_merge($existing, $this->savedfiles);
        $this->proposal->save();
        $this->savedfiles = [];
    }

    public function checkToggle()
    {
        if($this->stored) {
            $this->toggleStored();
        }
    }

    public function toggleStored()
    {
        $this->stored = !$this->stored;
    }

    public function clearUploadErrors(): void
    {
        $this->resetValidation(['decisionfiles', 'decisionfiles.*']);
        $this->resetErrorBag(['decisionfiles', 'decisionfiles.*']); // optional but safe
    }

    public function render()
    {
        return view('livewire.pp.proposal-decision-uploader');
    }
}
