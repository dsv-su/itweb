<?php

namespace App\Services\Send;

use App\Models\ProjectProposal;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FilesForRegistrator
{
    Protected ProjectProposal $proposal;

    public function __construct(string|ProjectProposal $proposal)
    {
        if ($proposal instanceof ProjectProposal) {
            $this->proposal = $proposal;
        } else {
            $this->proposal = ProjectProposal::findOrFail($proposal);
        }
    }

    public function storeFiles()
    {
        $budget = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::BUDGET);
        $final = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::FINAL);
        $files = array_merge($budget, $final);

        $originalfiles = $this->proposal->files;
        $zip = new ZipArchive;
        $zipFileName = "download/"  . $this->proposal->id .'/'. 'ProjectProposal-' . $this->proposal->name . '.zip';
        $zipFilePath = public_path($zipFileName);

        // Ensure the download directory exists
        if (!file_exists(public_path('download/' . $this->proposal->id .'/'))) {
            mkdir(public_path('download/' . $this->proposal->id .'/'), 0777, true);
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

    public function storeDecisionLetter()
    {
        $files = Storage::files(ProposalsDirectory::MAIN . $this->proposal->id . ProposalsDirectory::DECISION);

        $originalfiles = $this->proposal->files;
        $zip = new ZipArchive;
        $zipFileName = "download/"  . $this->proposal->id .'/'. 'ProjectProposal-' . $this->proposal->name . '.zip';
        $zipFilePath = public_path($zipFileName);

        // Ensure the download directory exists
        if (!file_exists(public_path('download/' . $this->proposal->id .'/'))) {
            mkdir(public_path('download/' . $this->proposal->id .'/'), 0777, true);
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
}
