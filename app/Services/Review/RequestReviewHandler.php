<?php
namespace App\Services\Review;

use App\Models\Dashboard;
use App\Models\FoComment;
use App\Models\HeadComment;
use App\Models\ManagerComment;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class RequestReviewHandler
{
    protected Dashboard $dashboard;
    protected User $reviewer;
    protected string $comment;
    protected WorkflowHandler $workflowhandler;
    protected string $decision;

    public function __construct(Dashboard $dashboard, User $reviewer, $comment, string $decision)
    {
        $this->dashboard = $dashboard;
        $this->reviewer = $reviewer;
        $this->comment = trim((string) $comment);
        $this->decision = $decision;

        $this->workflowhandler = new WorkflowHandler($this->dashboard->workflow_id);
    }

    public function review(): void
    {
        $this->assertValidDecision($this->decision);

        DB::transaction(function (): void {
            $this->registerCommentAndTransition();
        });
    }

    private function registerCommentAndTransition(): void
    {
        if ($this->dashboard->type !== 'travelrequest') {
            throw new RuntimeException('Unsupported dashboard type: ' . (string) $this->dashboard->type);
        }

        $tr = TravelRequest::query()->findOrFail($this->dashboard->request_id);

        $role = $this->getRole();
        if ($role === null) {
            throw new RuntimeException('Reviewer is not allowed to review this request in the current state.');
        }

        $commentId = match ($role) {
            'manager' => $this->managerComment('travelrequest', $tr->id, $this->reviewer->id, $this->comment)->id,
            'head' => $this->headComment('travelrequest', $tr->id, $this->reviewer->id, $this->comment)->id,
            'fo' => $this->foComment('travelrequest', $tr->id, $this->reviewer->id, $this->comment)->id,
            default => throw new RuntimeException('Unsupported role: ' . $role),
        };

        if ($role === 'manager') {
            $tr->manager_comment_id = $commentId;
        } elseif ($role === 'head') {
            $tr->head_comment_id = $commentId;
        } elseif ($role === 'fo') {
            $tr->fo_comment_id = $commentId;
        }

        $this->applyTransition($role, $this->decision);

        $tr->save();
    }

    private function getRole(): ?string
    {
        // Make this total: always return a string or null.
        $state = (string) $this->dashboard->state;
        $reviewerId = (string) $this->reviewer->id;

        if ($state === 'submitted' && (string) $this->dashboard->manager_id === $reviewerId) {
            return 'manager';
        }

        if ($state === 'manager_approved' && (string) $this->dashboard->head_id === $reviewerId) {
            return 'head';
        }

        if ($state === 'head_approved' && (string) $this->dashboard->fo_id === $reviewerId) {
            return 'fo';
        }

        return null;
    }

    private function applyTransition(string $role, string $decision): void
    {
        $map = [
            'manager' => [
                'approve' => 'ManagerApprove',
                'return'  => 'ManagerReturn',
                'deny'    => 'ManagerDeny',
            ],
            'head' => [
                'approve' => 'HeadApprove',
                'return'  => 'HeadReturn',
                'deny'    => 'HeadDeny',
            ],
            'fo' => [
                'approve' => 'FOApprove',
                'return'  => 'FOReturn',
                'deny'    => 'FODeny',
            ],
        ];

        if (!isset($map[$role][$decision])) {
            throw new InvalidArgumentException("Invalid decision '{$decision}' for role '{$role}'.");
        }

        $method = $map[$role][$decision];
        $this->workflowhandler->{$method}();
    }

    private function assertValidDecision(string $decision): void
    {
        if (!in_array($decision, ['approve', 'return', 'deny'], true)) {
            throw new InvalidArgumentException("Invalid decision: {$decision}");
        }
    }

    private function managerComment($type, $req_id, $role_id, $comment)
    {
        switch ($type) {
            case 'travelrequest':
                $id = ManagerComment::updateOrCreate(
                    ['reqid' => $req_id, 'user_id' => $role_id],
                    ['comment' => $comment]
                );
                break;
            default:
                throw new RuntimeException('Unsupported comment type: ' . (string) $type);
        }

        return $id;
    }

    private function foComment($type, $req_id, $role_id, $comment)
    {
        switch ($type) {
            case 'travelrequest':
                $id = FoComment::updateOrCreate(
                    ['reqid' => $req_id, 'user_id' => $role_id],
                    ['comment' => $comment]
                );
                break;
            default:
                throw new RuntimeException('Unsupported comment type: ' . (string) $type);
        }

        return $id;
    }

    private function headComment($type, $req_id, $role_id, $comment)
    {
        switch ($type) {
            case 'travelrequest':
                $id = HeadComment::updateOrCreate(
                    ['reqid' => $req_id, 'user_id' => $role_id],
                    ['comment' => $comment]
                );
                break;
            default:
                throw new RuntimeException('Unsupported comment type: ' . (string) $type);
        }

        return $id;
    }
}
