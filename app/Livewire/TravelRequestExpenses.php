<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\TravelRequest;
use Carbon\Carbon;
use Livewire\Component;

class TravelRequestExpenses extends Component
{
    public $total;
    public $flight;
    public $hotel;
    public $daily;
    public $conference;
    public $other_costs;
    public $days;
    public $countryname;
    public $departure, $return;
    public $tr;

    protected $listeners = [
        'selectedCountry',
        'changeStartDate' => 'changeStartDate',
        'changeEndDate' => 'changeEndDate'
    ];

    public function mount(?TravelRequest $tr = null)
    {
        $this->tr = $tr;
        $this->flight = $this->tr->flight ?? null;
        $this->hotel = $this->tr->hotel ?? null;
        $this->daily = $this->tr->daily ?? null;
        $this->countryname = $this->tr->country ?? null;
        $this->conference = $this->tr->conference ?? null;
        $this->other_costs = $this->tr->other_costs ?? null;
        $this->days = $this->tr->days ?? 0;
        $this->total = $this->tr->total ?? null;
        $this->departure = $this->tr?->departure
            ? Carbon::createFromTimestamp($this->tr->departure)->toDateString()
            : null;
        $this->return = $this->tr?->return
            ? Carbon::createFromTimestamp($this->tr->return)->toDateString()
            : null;

        $this->recalculateDays();
        $this->summarize();
    }

    public function changeStartDate($date)
    {
        $this->departure = $date;
        $this->recalculateDays();
        $this->summarize();
    }

    public function changeEndDate($date)
    {
        $this->return = $date;
        $this->recalculateDays();
        $this->summarize();
    }

    public function hydrate()
    {
        $this->recalculateDays();
        $this->summarize();
    }

    public function selectedCountry($id)
    {
        if ($id == 999) {
            $this->domestic();
        } else {
            $country = Country::find($id);

            if (!$country) {
                return;
            }

            $this->daily = $country->allowance;
            $this->countryname = $country->country;
            $this->summarize();
        }
    }

    public function domestic()
    {
        $this->daily = 290;
        $this->countryname = 'Sverige';
        $this->summarize();
    }

    public function getDays($value)
    {
        $this->days = $value;
        $this->summarize();
    }

    public function countryAllowance($id)
    {
        $country = Country::find($id);
        $this->daily = $country->allowance;
        $this->countryname = $country->country;
        $this->summarize();
    }

    public function updatedFlight()
    {
        $this->summarize();
    }

    public function updatedHotel()
    {
        $this->summarize();
    }

    public function updatedDaily()
    {
        $this->summarize();
    }

    public function updatedConference()
    {
        $this->summarize();
    }

    public function updatedOtherCosts()
    {
        $this->summarize();
    }

    public function render()
    {
        $this->summarize();
        return view('livewire.travel-request-expenses');
    }

    private function summarize()
    {
        $this->total = (int)$this->flight + (int)$this->hotel + ((int)$this->daily * (int)$this->days) + (int)$this->conference + (int)$this->other_costs;
    }

    private function recalculateDays()
    {
        if (!$this->departure || !$this->return) {
            $this->days = 0;
            return;
        }

        $departure = Carbon::createFromFormat('Y-m-d', $this->departure)->startOfDay();
        $return = Carbon::createFromFormat('Y-m-d', $this->return)->startOfDay();

        $this->days = max(0, (int) $departure->diffInDays($return));
    }
}
