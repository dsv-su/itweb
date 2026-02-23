<?php

namespace App\Livewire;

use App\Models\Dashboard;
use Livewire\WithPagination;
use Livewire\Component;

class RequestSearch extends Component
{
    use WithPagination;

    public $searchTerm;

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        return view('livewire.request-search', [
            'dashboards' => Dashboard::with(['user', 'travel'])
                ->where('name', 'like', $searchTerm)
                ->orWhereHas('user', function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', $searchTerm ?? '')
                        ->orWhere('email', 'LIKE', $searchTerm ?? '');
                })
                ->orWhereHas('travel', function ($query) use ($searchTerm) {
                    $query->where('project', 'LIKE', $searchTerm ?? '')
                        ->orWhere('country', 'LIKE', $searchTerm ?? '')
                        ->orWhere('purpose', 'LIKE', $searchTerm ?? '')
                        ->orWhere('id', 'LIKE', $searchTerm ?? '');
                })
                ->paginate(10),
        ]);
    }
}
