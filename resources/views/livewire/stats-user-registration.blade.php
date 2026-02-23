<div class="container">
    <h1>DSVIntranet is under construction</h1>
    <div class="chart-container">
        <x-chartjs-component :chart="$this->chart" />
    </div>
    <div class="grid grid-cols-1 mt-2">
        <div class="inline-flex items-center justify-center gap-x-1.5 text-blue-800 font-medium py-2 px-4  w-full text-center dark:text-white">
            <label for="start" class="text-xs">Start date:</label>
            <input class="text-xs border border-susecondary rounded-lg" wire:model.live="start" type="date" id="start">
        </div>
    </div>
</div>
