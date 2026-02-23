<?php

namespace App\Services\Proposal;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use Statamic\View\View;

class ProjectProposalFormService
{
    public function __construct(
        private ProjectProposalPrepare $proposalPrepare,
        private ProjectProposalCreateView $proposalCreateView,
    ) {}

    /**
     * Build view data for proposal form screens.
     */
    public function build(string $id, string $type, ?callable $mutate = null)
    {
        $viewData = $this->proposalPrepare->prepareProjectProposalData($id);

        // findOrFail ensures the view always gets a real model
        $viewData['proposal']  = ProjectProposal::query()->findOrFail($id);
        $viewData['dashboard'] = Dashboard::query()->where('request_id', $id)->first();
        $viewData['type']      = $type;

        if ($mutate) {
            $mutate($viewData);
        }

        //return $viewData;
        return $this->proposalCreateView->build('pp.create', 'mylayout', $viewData);
    }

}
