<?php

namespace App\Http\Controllers;

use App\Services\Proposal\ProjectProposalFormService;

class ProposalReportController extends Controller
{
    public function __construct(private ProjectProposalFormService $formService)
    {
        $this->middleware(['web', 'auth', 'dsv']);
    }

    public function pp_sent(string $id)     { return $this->formService->build($id, 'sent'); }
    public function pp_granted(string $id)  { return $this->formService->build($id, 'granted'); }
    public function pp_rejected(string $id) { return $this->formService->build($id, 'rejected'); }
}
