<?php

namespace App\Workflows\StatusUpdates;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use Workflow\Activity;
use InvalidArgumentException;

class PPStatusUpdateUsersStage1 extends Activity
{
    /** @var Dashboard Disabled  */

    protected Dashboard $dashboard;

    public function execute(string $recipient, string $stage, int $request): void
    {
        $this->loadDashboard($request);

        // Update pp status based on recipient type
        $this->updateProposal($recipient, $stage);
    }

    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
    }

    private function updateStatus(string $status)
    {
        $proposal = ProjectProposal::findOrFail($this->dashboard->request_id);
        $proposal->status = $status;
        $proposal->save();
    }

    private function updateProposal(string $recipient, string $stage): void
    {
        switch ($recipient) {
            case 'head':
                switch ($stage) {
                    case 'review':
                        $this->updateStatus('uh_review');
                        break;
                    case 'approved':
                        $this->updateStatus('uh_approved');
                        break;
                    case 'returned':
                        $this->updateStatus('uh_returned');
                        break;
                    case 'denied':
                        $this->updateStatus('uh_denied');
                        break;
                }
                break;
            case 'vice':
                switch ($stage) {
                    case 'review':
                        $this->updateStatus('vh_review');
                        break;
                    case 'approved':
                        $this->updateStatus('vh_approved');
                        break;
                    case 'returned':
                        $this->updateStatus('vh_returned');
                        break;
                    case 'denied':
                        $this->updateStatus('vh_denied');
                        break;
                }
                break;
            case 'fo':
                switch ($stage) {
                    case 'review':
                        $this->updateStatus('fo_review');
                        break;
                    case 'approved':
                        $this->updateStatus('fo_approved');
                        break;
                    case 'returned':
                        $this->updateStatus('fo_returned');
                        break;
                    case 'denied':
                        $this->updateStatus('fo_denied');
                        break;
                }
                break;
            default:
                throw new InvalidArgumentException("Invalid recipient type: $recipient");
        }
    }
}
