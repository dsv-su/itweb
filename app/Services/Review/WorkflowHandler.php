<?php

namespace App\Services\Review;

use Workflow\WorkflowStub;

class WorkflowHandler
{
    protected WorkflowStub $workflow;

    public function __construct(int $id)
    {
        $this->workflow = WorkflowStub::load($id);
    }

    //Manager
    public function managerApprove(): void { $this->workflow->manager_approve(); }
    public function managerReturn(): void { $this->workflow->manager_return(); }
    public function managerDeny(): void { $this->workflow->manager_deny(); }

    //FO
    public function foApprove(): void {  $this->workflow->fo_approve(); }
    public function foReturn(): void { $this->workflow->fo_return(); }
    public function foDeny(): void { $this->workflow->fo_deny(); }

    //Head
    public function headApprove(): void { $this->workflow->head_approve(); }
    public function headReturn(): void { $this->workflow->head_return(); }
    public function headDeny(): void { $this->workflow->head_deny(); }

    //Vice
    public function viceApprove(): void { $this->workflow->vice_approve(); }
    public function viceReturn(): void { $this->workflow->vice_return(); }
    public function viceDeny(): void { $this->workflow->vice_deny(); }

    public function Submitted()
    {
        $this->workflow->submit();
    }
    public function Completed()
    {
        $this->workflow->complete();
    }

    //Files have been uploaded
    public function uploadedFiles(): void { $this->workflow->setfilesUploaded(true); }
    public function removedFile(): void { $this->workflow->setfilesUploaded(false); }

    public function draftFileChanged(): void { $this->workflow->setDraftFilesChanged(true); }
    public function draftFileUnchanged(): void { $this->workflow->setDraftFilesChanged(false); }

    public function budgetFileChanged(): void { $this->workflow->setBudgetFilesChanged(true); }
    public function budgetFileUnchanged(): void { $this->workflow->setBudgetFilesChanged(false); }

    //Final
    public function FinalApprove()
    {
        $this->workflow->final_approve();
    }

    public function FinalReturn()
    {
        $this->workflow->final_return();
    }

    public function FinalDeny()
    {
        $this->workflow->final_deny();
    }
}
