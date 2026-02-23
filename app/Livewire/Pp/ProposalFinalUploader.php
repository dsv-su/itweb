<?php

namespace App\Livewire\Pp;

use App\Models\Dashboard;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ProposalFinalUploader extends Component
{
    use WithFileUploads;

    const PREAPPROVED = 'vice_approved';
    const COMPLETE = 'complete';
    const APPROVED = 'final_approved';

    public $proposal;
    public $dashboard;
    public $finalfiles = [];
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
            'finalfiles'   => 'array',
            'finalfiles.*' => 'file|max:20480|mimes:txt,pdf,doc,docx,ppt,pptx,odt,pages,zip,rar,rtf',
        ];
    }
    public function mount($proposal, string $type): void
    {
        $this->proposal  = $proposal;
        $this->type      = $type;
        $this->directory =
            ProposalsDirectory::MAIN.
            $proposal->id.
            ProposalsDirectory::FINAL;

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
            self::PREAPPROVED,
            self::COMPLETE,
            self::APPROVED,
        ], []);

        $this->allow = in_array($userId, $allowedUserIds)
            && in_array($this->dashboard->state, $allowedStates);
    }

    public function finishUpload($name, $tmpPath, $isMultiple)
    {
        $this->toggleStored();
        $this->cleanupOldUploads();
        $finalfiles = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        })->toArray();
        $this->emitSelf('upload:finished', $name, collect($finalfiles)->map->getFilename()->toArray());

        $finalfiles = array_merge($this->getPropertyValue($name), $finalfiles);
        $this->syncInput($name, $finalfiles);
    }

    public function storefiles()
    {
        $this->validate();
        foreach($this->finalfiles as $file) {
            $this->savedfiles[$file->getClientOriginalName()] = [
                'path' => $file->store(path: $this->directory),
                'tmp' => basename($file->getRealPath()),
                'size' => round($file->getSize()/1000),
                'date' => now()->format('d/m/Y'),
                'type' => 'final',
                'review' => 'pending',
                'uploader' => Auth::user()->name
            ];
        }

        //Update files to model
        $this->updateProposal();

        //Toggled buttons
        $this->toggleStored();
        $this->finalfiles = [];
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

    public function clearUploadErrors(): void
    {
        $this->resetValidation(['finalfiles', 'finalfiles.*']);
        $this->resetErrorBag(['finalfiles', 'finalfiles.*']); // optional but safe
    }

    public function toggleStored()
    {
        $this->stored = !$this->stored;
    }

    public function render()
    {
        return view('livewire.pp.proposal-final-uploader');
    }
}
