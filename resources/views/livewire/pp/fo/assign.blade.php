<span class="inline-flex flex-col items-start gap-2" x-on:click.stop x-on:keydown.stop>
    <span class="inline-flex items-center gap-2">
        <span class="font-semibold">{{ $assignedName }}</span>
        @if($canSwitchFo)
            <button type="button" wire:click="switchFo"
                    aria-expanded="{{ $switchingFo ? 'true' : 'false' }}"
                    aria-controls="proposal-switch-fo-{{ $proposal->id }}"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md border border-blue-300 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-blue-500 dark:text-blue-300 dark:hover:bg-gray-700">
                {{ __('Switch FO') }}
            </button>
        @endif
    </span>
    @if($canSwitchFo && $switchingFo)
        <span id="proposal-switch-fo-{{ $proposal->id }}" class="flex flex-wrap items-end gap-2 rounded-lg border border-blue-100 bg-blue-50 p-3 dark:border-gray-600 dark:bg-gray-900">
            <span class="block">
                <label for="proposal-new-fo-{{ $proposal->id }}" class="mb-1 block text-xs font-medium text-gray-900 dark:text-white">{{ __('Financial officer') }}</label>
                <select id="proposal-new-fo-{{ $proposal->id }}" wire:model="selectedFoId" class="block w-full rounded-md border-gray-300 bg-white py-1.5 text-xs text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">{{ __('Choose a financial officer') }}</option>
                    @foreach($financialOfficers as $officer)
                        <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                    @endforeach
                </select>
                @error('selectedFoId') <span role="alert" class="mt-1 block text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
            </span>
            <button type="button" wire:click="saveFo" wire:loading.attr="disabled" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50">{{ __('Save') }}</button>
            <button type="button" wire:click="cancelFoSwitch" wire:loading.attr="disabled" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">{{ __('Cancel') }}</button>
        </span>
    @endif
    @if($foStatus)
        <span role="status" class="text-xs text-gray-600 dark:text-gray-300">{{ $foStatus }}</span>
    @endif
</span>
