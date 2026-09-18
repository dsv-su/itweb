<?php

namespace App\Livewire;

use App\Models\Dashboard;
use App\Traits\DashboardRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use DashboardRequests, WithPagination;

    public string $category = 'all';

    public string $search = '';

    public string $type = '';

    public string $state = '';

    public string $feedback = '';

    public function refreshNotifications(): void
    {
        $this->feedback = __('Notifications refreshed.');
    }

    public function updated($property): void
    {
        if (in_array($property, ['category', 'search', 'type', 'state'], true)) {
            $this->resetPage();
        }
    }

    public function markRead(int $id): void
    {
        abort_unless(Auth::check(), 403);
        // Read status belongs to the submitter; reviewers must not change it.
        $notification = Dashboard::where('user_id', Auth::id())->find($id);
        if (! $notification || $notification->status === 'read') {
            return;
        }
        Dashboard::withoutTimestamps(fn () => $notification->update(['status' => 'read']));
        $this->feedback = __('Marked :name as read.', ['name' => $notification->name]);
    }

    public function render()
    {
        abort_unless(Auth::check(), 403);
        $reviewIds = $this->Dashboardtask(Auth::id())->pluck('id');
        $headRequests = Dashboard::query()->where(function ($query) {
            $query->where(fn ($q) => $q->where('type', 'travelrequest')->where('head_id', Auth::id()))
                ->orWhere(fn ($q) => $q->where('type', 'projectproposal')->whereJsonContains('unit_heads', Auth::id()));
        });
        $approvedIds = (clone $headRequests)->where(function ($query) {
            $query->where(fn ($q) => $q->where('type', 'travelrequest')->whereIn('state', ['head_approved', 'fo_approved']))
                ->orWhere(fn ($q) => $q->where('type', 'projectproposal')
                    ->whereJsonContains('unit_head_approved', [Auth::id() => 1])
                    ->whereIn('state', ['complete', 'head_approved', 'fo_approved', 'final_approved', 'sent', 'granted']));
        })->pluck('id');
        $visible = Dashboard::query()->where(function ($query) use ($reviewIds, $approvedIds) {
            $query->where('user_id', Auth::id())->orWhereIn('id', $reviewIds)->orWhereIn('id', $approvedIds);
        });
        $returnedStates = ['manager_returned', 'head_returned', 'fo_returned', 'vice_returned', 'final_returned',
            'manager_denied', 'head_denied', 'fo_denied', 'vice_denied', 'final_denied', 'denied'];
        $mine = (clone $visible)->where('user_id', Auth::id());
        $counts = [
            'all' => (clone $visible)->count(),
            'review' => $reviewIds->count(),
            'returned' => (clone $mine)->whereIn('state', $returnedStates)->count(),
            'mine' => (clone $mine)->count(),
            'approved' => $approvedIds->count(),
        ];
        $states = (clone $visible)->reorder()->distinct()->pluck('state')->map(fn ($state) => (string) $state)->sort()->values();
        $query = (clone $visible)->with(['user', 'travel']);
        match ($this->category) {
            'review' => $query->whereIn('id', $reviewIds),
            'returned' => $query->where('user_id', Auth::id())->whereIn('state', $returnedStates),
            'mine' => $query->where('user_id', Auth::id()),
            'approved' => $query->whereIn('id', $approvedIds),
            default => null,
        };
        $query->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
            ->when($this->state !== '', fn ($q) => $q->where('state', $this->state))
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->where(fn ($q) => $q->where('name', 'like', $term)
                    ->orWhere('id', 'like', $term)
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', $term)));
            });

        return view('livewire.notifications', [
            'notifications' => $query->orderByDesc('updated_at')->orderByDesc('id')->paginate(15),
            'counts' => $counts,
            'states' => $states,
            'reviewIds' => $reviewIds,
            'isHead' => $headRequests->exists(),
        ]);
    }
}
