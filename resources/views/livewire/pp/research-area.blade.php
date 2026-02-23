<div>
    <div class="mt-5 space-y-4">
        <div class="mt-3">
            <div class="border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
                @foreach($areas as $index => $area)
                    <div class="flex items-center gap-2 mb-2">

                        <input type="text"
                               wire:model="areas.{{ $index }}.name"
                               class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                  focus:ring-primary-600 focus:border-primary-600 w-full p-2.5
                                  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200
                                  dark:focus:ring-primary-500 dark:focus:border-primary-500"
                               placeholder="Enter area name">

                        <button type="button"
                                wire:click="updateArea({{ $index }})"
                           class="inline-flex items-center px-2 py-2 bg-white border border-green-600 text-green-600 rounded-md font-semibold text-[0.65rem]
                            uppercase tracking-widest hover:bg-green-600 hover:text-white active:bg-green-700 focus:outline-none focus:border-green-800 focus:ring ring-green-300
                            disabled:opacity-25 transition ease-in-out duration-150">
                            Update
                        </button>
                    </div>
                @endforeach
                    <div class="mt-3 flex items-center gap-2">
                        <input wire:model="add_area"
                            type="text"
                               class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500
                                focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900
                                dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                               placeholder="Add Research Area">

                        <button type="button"
                                wire:click="addArea"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                                 uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                                 disabled:opacity-25 transition ease-in-out duration-150">
                            Add
                        </button>
                    </div>
                    <div class=" p-1 dark:bg-slate-800 dark:border-gray-700">
                        <button type="button" wire:click="resetAreas"
                                class="inline-flex items-center px-1 py-0.5 bg-white border border-red-600 text-red-600 rounded-md font-semibold text-[0.5rem]
                                uppercase tracking-widest hover:bg-red-600 hover:text-white active:bg-red-700 focus:outline-none focus:border-red-800 focus:ring ring-red-300
                                disabled:opacity-25 transition ease-in-out duration-150">
                            Reset
                        </button>

                    </div>
            </div>
        </div>
    </div>
</div>
