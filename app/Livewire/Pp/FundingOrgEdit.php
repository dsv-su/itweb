<?php

namespace App\Livewire\Pp;

use App\Exports\FundingOrganizationExport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class FundingOrgEdit extends Component
{
    use WithFileUploads;
    public array $fundingorg   = [];    // flat data for wire:model
    public int   $page         = 1;     // 1-indexed current page
    public int   $rowsPerPage  = 5;     // how many *rows* per page
    public int   $itemsPerRow  = 2;     // how many items per row
    public $add_fundingorg;
    public $countorgs;
    public $upload;

    protected $listeners = [
        'add_refresh' => '$refresh'
    ];

    public function nextPage()
    {
        if ($this->page < $this->totalPages) {
            $this->page++;
        }
    }

    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function getTotalPagesProperty(): int
    {
        // total number of rows
        $allRows = collect($this->fundingorg)
            ->chunk($this->itemsPerRow)
            ->count();

        return (int) ceil($allRows / $this->rowsPerPage);
    }

    public function updateFundingOrg($index)
    {
        // Ensure we access the correct key from the array
        $fundingData = $this->fundingorg[$index];

        // Find the Funding Org model and update it
        $fundorg = \App\Models\FundingOrganization::find($fundingData['id']);
        if ($fundorg) {
            $fundorg->name = $fundingData['name'];
            $fundorg->save();
        }
    }

    public function uploadFundingFile()
    {
        $this->validate([
            'upload' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB
        ]);

        // Store in storage/app/public/exports
        $this->upload->storeAs('exports', 'funding_org.xlsx', 'public');

        $this->reset('upload');

        session()->flash('message', 'File uploaded to exports/funding_org.xlsx');
    }

    public function removeFundingOrg($index)
    {
        // Ensure we access the correct key from the array
        $fundingData = $this->fundingorg[$index];

        // Find the Funding Org model and update it
        $fundorg = \App\Models\FundingOrganization::destroy($fundingData['id']);
        $this->dispatch('add_refresh');
    }

    public function addFundingOrg()
    {
        $fundorg = \App\Models\FundingOrganization::create([
            'name' => $this->add_fundingorg,
        ]);
        $this->add_fundingorg = '';
        $this->dispatch('add_refresh');
    }

    public function saveToFile()
    {
        Excel::store(
            new FundingOrganizationExport(),
            'exports/funding_org.xlsx',
            'public' // storage/app/public/exports/funding_org.xlsx
        );
    }

    public function clearFunding()
    {
        Artisan::call('clear-funding');

        $output = Artisan::output();

        session()->flash('message', $output);
    }

    public function downloadFile()
    {
        $path = 'exports/funding_org.xlsx';
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $this->addError('download', 'File not found. Generate it first.');
            return;
        }

        // Livewire can return a normal response
        return $disk->download($path, 'funding_org.xlsx');
    }

    public function render()
    {
        $flat = \App\Models\FundingOrganization::orderBy('name','asc')
            ->get(['id','name'])
            ->map(fn($o) => ['id'=>$o->id,'name'=>$o->name])
            ->toArray();

        // count
        $this->countorgs = count($flat);
        // bind it so wire:model still works
        $this->fundingorg = $flat;

        // Chunk into *rows* of $itemsPerRow
        $allRows = collect($flat)
            ->chunk($this->itemsPerRow);

        // Paginate those rows into *pages* of $rowsPerPage rows
        $fundingChunks = $allRows
            ->forPage($this->page, $this->rowsPerPage);

        // Pass to the view
        return view('livewire.pp.funding-org-edit', [
            'fundingChunks' => $fundingChunks,
            'totalPages'    => $this->totalPages,
            'totalOrgs'     => $this->countorgs,
        ]);
    }
}
