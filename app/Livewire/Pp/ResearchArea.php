<?php

namespace App\Livewire\Pp;

use App\Models\DsvBudget;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class ResearchArea extends Component
{
    public $areas = [];
    public $add_area;

    protected $listeners = [
        'add_refresh' => '$refresh'
    ];

    public function updateArea($index)
    {
        // Ensure we access the correct key from the array
        $areaData = $this->areas[$index];

        // Find the ResearchArea model and update it
        $area = \App\Models\ResearchArea::find($areaData['id']);
        if ($area) {
            $area->name = $areaData['name'];
            $area->save();
        }
    }

    public function addArea()
    {
        $flight = \App\Models\ResearchArea::create([
            'name' => $this->add_area,
        ]);

        //Update db
        $budget = DsvBudget::find(1);

        $data = $budget->research_area ?? [];

        $newArea = $this->add_area;

        $data[$newArea] = [
            'preapproved'   => 0,
            'budget_sek'    => 0,
            'budget_eur'    => 0,
            'budget_usd'    => 0,
            'phd'           => 0,
            'cost_sek'      => 0,
            'cost_eur'      => 0,
            'cost_usd'      => 0,
            'granted_sek'   => 0,
            'granted_eur'   => 0,
            'granted_usd'   => 0,
            'phd_promised'  => 0,
        ];

        $budget->research_area = $data;
        $budget->save();

        $this->add_area = '';
        $this->dispatch('add_refresh');
    }

    public function remove($id)
    {
        dd($id);
    }

    public function resetAreas()
    {
        /*Artisan::call('clear-areas');
        $this->reset();*/
        Artisan::call('clear-areas');

        $this->areas = \App\Models\ResearchArea::query()
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();

        $this->add_area = '';
    }

    public function render()
    {
        $ns = \App\Models\ResearchArea::all();
        foreach($ns as $key => $n) {
            $this->areas[$key]['id'] = $n->id;
            $this->areas[$key]['name'] = $n->name;
        }

        return view('livewire.pp.research-area');
    }
}
