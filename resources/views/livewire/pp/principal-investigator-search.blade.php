<div class="w-full sm:col-span-2">
    <label for="investigator-search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Search SUKAT') }}</label>
    <input id="investigator-search" type="search" wire:model.live.debounce.300ms="search"
           wire:keydown.enter.prevent autocomplete="off" placeholder="{{ __('Search by name or email') }}"
           class="w-full p-2.5 text-sm rounded-lg border border-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-white">
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('The selected investigator will own the proposal and receive its notifications.') }}</p>
    <div wire:loading wire:target="search,select" class="text-sm">{{ __('Searching SUKAT…') }}</div>
    @if($results)
        <ul class="border rounded-lg divide-y dark:text-white">
            @foreach($results as $person)
                <li wire:key="pi-{{ $person['uid'] }}">
                    <button type="button" wire:click="select(@js($person['uid']))" class="w-full p-2 text-left hover:bg-blue-100 dark:hover:bg-gray-700">
                        {{ $person['name'] }} — {{ $person['email'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    @elseif(mb_strlen(trim($search)) >= 2)
        <p class="text-sm">{{ __('No investigators found.') }}</p>
    @endif
    @error('principal_investigator_uid') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
