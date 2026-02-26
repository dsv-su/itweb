<?php

namespace App\Livewire\Pp;

use App\Models\BudgetTemplate;
use App\Models\Dashboard;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class BudgetTemplateUploader extends Component
{
    use WithFileUploads;

    public $templatefiles = [];
    public $template;
    public array $templateLang = [];
    public array $langKeyMap = [];
    public $savedfiles = [];
    public $stored = false;
    public $allow;
    public $directory;

    protected $listeners = [
        'upload_refresh' => '$refresh'
    ];

    public function mount($template)
    {
        $this->template = $template;

        foreach (($this->template->files ?? []) as $filename => $file) {
            $safeKey = 'f_' . md5((string) $filename);
            $this->langKeyMap[$safeKey] = $filename;
            $this->templateLang[$safeKey] = $file['type'] ?? 'eng';
        }

        $this->directory = '/public'. ProposalsDirectory::BUDGET_TEMPLATE;
        //$this->template = Storage::files($this->directory);

        $this->allowUpload();
    }

    public function updated($name, $value)
    {
        if (!str_starts_with($name, 'templateLang.')) return;

        $safeKey = substr($name, strlen('templateLang.'));
        $filename = $this->langKeyMap[$safeKey] ?? null;
        if (!$filename) return;

        $files = $this->template->files ?? [];
        if (isset($files[$filename])) {
            $files[$filename]['type'] = $value;
            $this->template->files = $files;
            $this->template->save();
        }
    }
    public function allowUpload()
    {
        $user = Auth::user();
        $allowed_roles = [$this->getViceHeadUserId()];
        $this->allow = $user && (
                $user->isSuperAdmin() || in_array($user->id, $allowed_roles, true)
            );
    }

    public function finishUpload($name, $tmpPath, $isMultiple)

    {
        $this->toggleStored();
        $this->cleanupOldUploads();
        $templatefiles = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        })->toArray();
        $this->emitSelf('upload:finished', $name, collect($templatefiles)->map->getFilename()->toArray());

        $templatefiles = array_merge($this->getPropertyValue($name), $templatefiles);
        $this->syncInput($name, $templatefiles);
    }

    public function storefiles()
    {
        foreach($this->templatefiles as $file) {
            $name = $file->getClientOriginalName();

            $this->savedfiles[$name] = [
                'name' => $name,
                'path' => $file->store(path: $this->directory),
                'tmp' => basename($file->getRealPath()),
                'size' => round($file->getSize()/1000),
                'date' => now()->format('Y-m-d'),
                'type' => 'eng',
                'review' => 'Active',
                'uploader' => Auth::user()->name,
            ];

            $this->templateLang[$name] = 'eng';
        }

        $this->updateFileArray();

        //Toggled buttons
        $this->toggleStored();
        $this->templatefiles = [];
        $this->dispatch('upload_refresh');
    }

    public function checkToggle()
    {
        if($this->stored) {
            $this->toggleStored();
        }
    }

    public function updateFileArray()
    {
        $this->template->files = array_merge($this->template->files, $this->savedfiles);
        $this->savedfiles = [];
        $this->template->save();
    }

    public function toggleStored()
    {
        $this->stored = !$this->stored;
    }

    public function removefile($id)
    {
        // Get the current files array
        $files = $this->template->files;
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
        $this->template->files = $files;

        // Persist changes to the database
        $this->template->save();
    }

    public function downloadfile($id)
    {
        // Get the current files array
        $files = $this->template->files;
        $downloadfile = $files[$id]['path'];

        return Storage::download($downloadfile, $id);
    }

    public function render()
    {
        return view('livewire.pp.budget-template-uploader');
    }

    private function getViceHeadUserId(): ?string
    {
        return DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
    }
}
