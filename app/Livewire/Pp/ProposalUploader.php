<?php

namespace App\Livewire\Pp;

use App\Models\Dashboard;
use App\Services\Review\WorkflowHandler;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use ZipArchive;

class ProposalUploader extends Component
{
    use WithFileUploads;

    const PREAPPROVED = 'vice_approved';
    const SUBMITTED = 'submitted';
    const COMPLETE = 'complete';
    const APPROVED = 'final_approved';

    public $proposal;
    public $dashboard;
    public $files = [];
    public $savedfiles = [];
    public $stored = false;
    public $directory;
    public $allow;
    public $type;
    public $resumed = ['pending', 'vice_returned', 'head_returned', 'fo_returned', 'final_returned'];

    protected $listeners = [
        'upload_refresh' => '$refresh'
    ];

    protected function rules(): array
    {
        return [
            'files'   => 'array',
            'files.*' => 'file|max:20480|mimes:txt,pdf,doc,docx,ppt,pptx,odt,pages,zip,rar,rtf',
        ];
    }
    public function mount($proposal, string $type): void
    {
        $this->proposal  = $proposal;
        $this->type      = $type;
        $this->directory =
            ProposalsDirectory::MAIN.
            $proposal->id.
            ProposalsDirectory::DRAFT;

        $this->dashboard = Dashboard::firstWhere('request_id', $proposal->id);

        if ($this->dashboard) {
            $this->allowUpload();
            return;
        }

        $this->allow = true;
    }

    public function checkFileStatus()
    {
        // No dashboard
        if (!$this->dashboard) {
            return 0;
        }

        // Pending dashboard
        if ($this->dashboard->state === 'pending') {
            return 0;
        }

        // Files: ensure array
        $files = $this->proposal->files;
        $files = is_array($files) ? $files : [];

        $isUploaded = count($files) >= 2;

        // Side-effect: notify workflow if workflow_id exists
        if (!empty($this->dashboard->workflow_id)) {
            $workflowHandler = new WorkflowHandler($this->dashboard->workflow_id);

            if ($isUploaded) {
                $workflowHandler->UploadedFiles();
            } else {
                $workflowHandler->RemovedFile();
            }
        }

        return $this->reportStageStatus($isUploaded ? 'uploaded' : 'waiting');
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
            self::SUBMITTED,
            self::APPROVED,
        ], $this->resumed ?? []);

        $this->allow = in_array($userId, $allowedUserIds)
            && in_array($this->dashboard->state, $allowedStates);
    }

    public function finishUpload($name, $tmpPath, $isMultiple)
    {
        $this->toggleStored();
        $this->cleanupOldUploads();
        $files = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        })->toArray();
        $this->emitSelf('upload:finished', $name, collect($files)->map->getFilename()->toArray());

        $files = array_merge($this->getPropertyValue($name), $files);
        $this->syncInput($name, $files);
    }

    public function storefiles()
    {
        $this->validate();
        foreach($this->files as $file) {
            $this->savedfiles[$file->getClientOriginalName()] = [
                'path' => $file->store(path: $this->directory),
                'tmp' => basename($file->getRealPath()),
                'size' => round($file->getSize()/1000),
                'date' => now()->format('Y-m-d'),
                'type' => 'draft',
                'review' => 'pending',
                'uploader' => Auth::user()->name
                ];
        }

        //Update files to model
        $this->updateProposal();
        $this->checkFileStatus();

        //Toggled buttons
        $this->toggleStored();
        $this->files = [];
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
        $this->resetValidation(['files', 'files.*']);
        $this->resetErrorBag(['files', 'files.*']); // optional but safe
    }
    public function removefile($id)
    {
        // Clear validation errors related to uploads
        $this->resetValidation(['files', 'files.*']);     // Livewire v2+
        $this->resetErrorBag(['files', 'files.*']);       // extra-safe

        // Get the current files array
        $files = $this->proposal->files;
        $remove = $files[$id]['path'];

        //For debugging
        //$livewireID = $files[$id]['path'];
        //$tmp = $files[$id]['tmp'];

        if (Storage::exists($remove)) {
            Storage::delete($remove);
        }

        // Remove the specific item by key
        if (isset($files[$id])) {
            unset($files[$id]);
        }

        // Save the modified array back to the property
        $this->proposal->files = $files;

        // Persist changes to the database
        $this->proposal->save();
        $this->checkFileStatus();
    }

    public function removefolder()
    {
        $this->resetValidation(['files', 'files.*']);
        $this->resetErrorBag(['files', 'files.*']);

        $this->proposal->files = [];
        Storage::deleteDirectory(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::DRAFT);
        Storage::deleteDirectory(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::BUDGET);
        Storage::deleteDirectory(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::FINAL);
        $this->proposal->save();
        $this->files = [];
        $this->reportStageStatus('pending');
    }

    public function downloadfile($id)
    {
        // Get the current files array
        $files = $this->proposal->files;
        $downloadfile = $files[$id]['path'];

        //return Storage::download($downloadfile, $id);
        return Storage::download($downloadfile, $files[$id]['original'] ?? $id);

    }

    public function downloadfolder()
    {
        $draft = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::DRAFT);
        $budget = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::BUDGET);
        $final = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::FINAL);
        $files = array_merge($draft, $budget, $final);

        $originalfiles = $this->proposal->files;
        $zip = new ZipArchive;
        $zipFileName = "download/" . 'ProjectProposal-' . $this->proposal->name . '.zip';
        $zipFilePath = public_path($zipFileName);

        // Ensure the download directory exists
        if (!file_exists(public_path('download'))) {
            mkdir(public_path('download'), 0777, true);
        }

        // Open the zip file for writing
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = Storage::path($file); // Get absolute path
                // Match name from model
                foreach ($originalfiles as $key => $orignal) {
                    if(basename($orignal['path']) == basename($file)) {
                        $set_zipname = $key;
                    }
                }
                // Create zip
                if (file_exists($filePath)) { // Check if file exists
                    $zip->addFile($filePath, $set_zipname);
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Could not create zip file'], 500);
        }

        // Return response with download and delete after sending
        return response()->download($zipFilePath)->deleteFileAfterSend(true);

    }

    public function render()
    {
        return view('livewire.pp.proposal-uploader');
    }
}
