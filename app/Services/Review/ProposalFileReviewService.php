<?php

namespace App\Services\Review;

use App\Models\ProjectProposal;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProposalFileReviewService
{
    protected ProjectProposal $proposal;

    /**
     * ProposalFileReviewService constructor.
     *
     * @param  string|ProjectProposal  $proposal  Either an UID or a loaded ProjectProposal.
     * @throws ModelNotFoundException
     */
    public function __construct(string|ProjectProposal $proposal)
    {
        if ($proposal instanceof ProjectProposal) {
            $this->proposal = $proposal;
        } else {
            $this->proposal = ProjectProposal::findOrFail($proposal);
        }
    }

    /**
     * Approve all files of a given type that are still pending review.
     *
     * @param  string  $type      The file-type key to filter on (e.g. 'budget').
     * @param  string  $from      Current review status (default 'pending').
     * @param  string  $to        New review status (default 'approved').
     * @return $this
     */
    public function approvePendingByType(
        string $type,
        string $from = 'pending',
        string $to   = 'approved'
    ): self {
        $files = $this->proposal->files;

        foreach ($files as $name => &$data) {
            if (
                isset($data['type'], $data['review'])
                && $data['type'] === $type
                && $data['review'] === $from
            ) {
                $data['review'] = $to;
            }
        }
        unset($data);

        $this->proposal->files = $files;
        $this->proposal->save();

        return $this;
    }

    /**
     * Approve a single file by filename.
     *
     * @param  string  $filename
     * @param  string  $to       New review status (default 'approved').
     * @return $this
     */
    public function approveFile(string $filename, string $to = 'approved'): self
    {
        $files = $this->proposal->files;

        if (isset($files[$filename])) {
            $files[$filename]['review'] = $to;
            $this->proposal->files = $files;
            $this->proposal->save();
        }

        return $this;
    }
}
