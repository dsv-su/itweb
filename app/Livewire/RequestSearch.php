<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Livewire\WithPagination;
use Livewire\Component;

class RequestSearch extends Component
{
    use WithPagination;

    public $searchTerm;
    public string $requestType = 'travelrequest';

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
            'dashboards' => Dashboard::with(['user', 'travel'])
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
