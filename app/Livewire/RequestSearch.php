<?php

namespace App\Livewire;

use App\Models\Dashboard;
use App\Models\FoComment;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Livewire\Component;

class RequestSearch extends Component
{
    use WithPagination;

    public $searchTerm;
    public string $requestType = 'travelrequest';

    #[Locked]
    public ?int $switchingFoId = null;
    public string $selectedFoId = '';
    public string $foStatus = '';

    public function switchFo(int $dashboardId): void
    {
        abort_unless(auth()->user()?->isFO(), 403);
        $dashboard = Dashboard::where('type', 'travelrequest')->findOrFail($dashboardId);
        $this->resetValidation();
        $this->foStatus = '';
        $this->switchingFoId = $dashboard->id;
        $this->selectedFoId = (string) $dashboard->fo_id;
    }

    public function cancelFoSwitch(): void
    {
        $this->reset('switchingFoId', 'selectedFoId');
        $this->resetValidation();
    }

    public function saveFo(): void
    {
        abort_unless(auth()->user()?->isFO(), 403);
        $this->validate([
            'selectedFoId' => [
                'required', 'string', 'exists:users,id',
                Rule::exists('group_user', 'user_id')->where('group_id', 'ekonomi'),
            ],
        ], [], ['selectedFoId' => __('Financial officer')]);

        $changed = DB::transaction(function () {
            $dashboard = Dashboard::where('type', 'travelrequest')
                ->lockForUpdate()->findOrFail($this->switchingFoId);
            TravelRequest::findOrFail($dashboard->request_id);
            if ((string) $dashboard->fo_id === $this->selectedFoId) {
                return false;
            }

            $previous = User::find($dashboard->fo_id);
            $next = User::findOrFail($this->selectedFoId);
            FoComment::create([
                'reqid' => $dashboard->request_id,
                'user_id' => auth()->id(),
                'comment' => __('Financial officer changed from :previous to :next.', [
                    'previous' => $previous ? $previous->name.' ('.$previous->id.')' : ($dashboard->fo_id ?: __('Unassigned')),
                    'next' => $next->name.' ('.$next->id.')',
                ]),
            ]);
            $dashboard->update(['fo_id' => $next->id]);

            return true;
        });

        $this->cancelFoSwitch();
        $this->foStatus = $changed ? __('Financial officer updated.') : __('Financial officer unchanged.');
    }

    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }

    public function updatedRequestType(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $requestType = in_array($this->requestType, ['travelrequest', 'projectproposal'], true)
            ? $this->requestType
            : 'travelrequest';

        return view('livewire.request-search', [
            'financialOfficers' => $this->switchingFoId !== null && auth()->user()?->isFO()
                ? User::whereIn('id', DB::table('group_user')->where('group_id', 'ekonomi')->select('user_id'))
                    ->orderBy('name')->get()
                : collect(),
            'dashboards' => Dashboard::with(['user', 'travel', 'financialOfficer'])
                ->where('type', $requestType)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm)
                        ->orWhereHas('user', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', $searchTerm ?? '')
                                ->orWhere('email', 'LIKE', $searchTerm ?? '');
                        })
                        ->orWhereHas('travel', function ($query) use ($searchTerm) {
                            $query->where('project', 'LIKE', $searchTerm ?? '')
                                ->orWhere('country', 'LIKE', $searchTerm ?? '')
                                ->orWhere('purpose', 'LIKE', $searchTerm ?? '')
                                ->orWhere('id', 'LIKE', $searchTerm ?? '');
                        });
                })
                ->paginate(10),
        ]);
    }
}
