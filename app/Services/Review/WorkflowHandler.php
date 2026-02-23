<?php

namespace App\Services\Review;

use Workflow\WorkflowStub;

class WorkflowHandler
{
    protected $workflow;

    public function __construct($id)
    {
        $this->workflow = WorkflowStub::load($id);
    }

    //Manager
    public function ManagerApprove()
    {
        $this->workflow->manager_approve();
    }

    public function ManagerReturn()
    {
        $this->workflow->manager_return();
    }

    public function ManagerDeny()
    {
        $this->workflow->manager_deny();
    }

    //FO
    public function FOApprove()
    {
        $this->workflow->fo_approve();
    }

    public function FOReturn()
    {
        $this->workflow->fo_return();
    }

    public function FODeny()
    {
        $this->workflow->fo_deny();
    }

    //Head
    public function HeadApprove()
    {
        $this->workflow->head_approve();
    }

    public function HeadReturn()
    {
        $this->workflow->head_return();
    }

    public function HeadDeny()
    {
        $this->workflow->head_deny();
    }

    //Vice
    public function ViceApprove()
    {
        $this->workflow->vice_approve();
    }

    public function ViceReturn()
    {
        $this->workflow->vice_return();
    }

    public function ViceDeny()
    {
        $this->workflow->vice_deny();
    }

    public function Submitted()
    {
        $this->workflow->submit();
    }
    public function Completed()
    {
        $this->workflow->complete();
    }

    //Files have been uploaded
    public function UploadedFiles()
    {
        $this->workflow->setfilesUploaded(true);
    }

    //A File has been removed
    public function RemovedFile()
    {
        $this->workflow->setfilesUploaded(false);
    }

    public function DraftFileChanged()
    {
        $this->workflow->setDraftFilesChanged(true);
    }

    public function DraftFileUnchanged()
    {
        $this->workflow->setDraftFilesChanged(false);
    }

    public function BudgetFileChanged()
    {
        $this->workflow->setBudgetFilesChanged(true);
    }

    public function BudgetFileUnchanged()
    {
        $this->workflow->setBudgetFilesChanged(false);
    }

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
