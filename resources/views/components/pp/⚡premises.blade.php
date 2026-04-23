<?php

use App\Models\SettingsOh;
use Livewire\Component;

new class extends Component {
    public $type;
    public $premisescost;
    public $proposal;

    protected $listeners = [
        'progress-refresh' => '$refresh'
    ];

    public function mount($type, $proposal)
    {
        $this->type = $type;
        $this->proposal = $proposal;
        $oh_settings = SettingsOh::first();
        if ($this->proposal) {
            $this->premisescost = $proposal->pp['oh_premises'] ?? $oh_settings->oh_premises;
        }
    }

};
?>

<div>
    <label for="oh_cost" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __('Percent of Premises costs covered') }}
        <span class="text-red-600"> *</span>
        <button id="premises-button"
                data-modal-target="premises-modal"
                data-modal-toggle="premises-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                      d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>

    <div class="flex items-center w-full">
        <input type="number"
               wire:model.live="premisescost"
               name="oh_premises"
               class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                      block w-[calc(100%-32px)] p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                      dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="Premises cost"
               @if(in_array($type, ['preapproval', 'saved', 'complete', 'edit', 'resume']))
                   required
               @else
                   readonly
            @endif >
        <span class="inline-block ml-2 text-gray-900 dark:text-gray-200">%</span>
        @error('premisescost')
        <p class="mt-3 text-sm leading-6 text-red-600"
           x-init="$el.closest('form').scrollIntoView()">{{__("This should not exceed OH costs of DSV 56%")}}</p>
        @enderror
    </div>
</div>
