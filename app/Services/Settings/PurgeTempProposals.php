<?php

namespace App\Services\Settings;

use App\Models\ProjectProposal;
use Illuminate\Support\Facades\Storage;

class PurgeTempProposals
{
    public function cleanUpOld()
    {
        // Fetch only the proposals that are pending
        $proposals = ProjectProposal::where('status_stage3', 'pending')->get();

        foreach ($proposals as $proposal) {
            Storage::deleteDirectory(ProposalsDirectory::MAIN . $proposal->id);
        }

        // Delete from the database after cleaning up directories
        ProjectProposal::where('status_stage3', 'pending')->delete();
    }
}

