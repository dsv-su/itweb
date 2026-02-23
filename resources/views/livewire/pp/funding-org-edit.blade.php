<div>
    <div class="mt-5 space-y-4">
        <div class="mt-3">
            <div class="border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
                <span class="mb-2 inline-flex items-center px-3 py-1.5 rounded-md border border-blue-200 bg-blue-50 text-blue-900 text-sm font-semibold
                            dark:border-blue-800 dark:bg-blue-950 dark:text-blue-100">
                    Total: {{ $totalOrgs }}
                </span>
            @foreach($fundingChunks as $chunk)
                        @foreach($chunk as $index => $area)
                            <div class="flex-1 min-w-[200px] flex items-center gap-2 mb-2">
                                <input
                                    type="text"
                                    wire:model="fundingorg.{{ $index }}.name"
                                    class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                         focus:ring-primary-600 focus:border-primary-600 w-full p-2.5
                                         dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200
                                         dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Enter organization name">

                                <button
                                    type="button"
                                    wire:click="updateFundingOrg({{ $index }})"
                                    class="inline-flex items-center px-2 py-2 bg-white border border-green-600 text-green-600 rounded-md font-semibold text-[0.65rem]
                                         uppercase tracking-widest hover:bg-green-600 hover:text-white active:bg-green-700 focus:outline-none focus:border-green-800 focus:ring ring-green-300
                                         disabled:opacity-25 transition ease-in-out duration-150">
                                    Update
                                </button>

                                <button
                                    type="button"
                                    wire:click="removeFundingOrg({{ $index }})"
                                    class="inline-flex items-center px-2 py-2 bg-white border border-red-600 text-red-600 rounded-md font-semibold text-[0.65rem]
                                         uppercase tracking-widest hover:bg-red-600 hover:text-white active:bg-red-700 focus:outline-none focus:border-red-800 focus:ring ring-red-300
                                         disabled:opacity-25 transition ease-in-out duration-150">
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    {{--}}</div>{{--}}
                @endforeach


                <div class="mt-3 flex items-center gap-2">
                    <input
                        wire:model="add_fundingorg"
                        type="text"
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500
                               focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900
                               dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Add Funding organization">

                    <button
                        type="button"
                        wire:click="addFundingOrg"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                               uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                               disabled:opacity-25 transition ease-in-out duration-150">
                        Add
                    </button>
                </div>

                <div class="mt-2 mb-4 flex justify-between items-center">
                    <button
                        type="button"
                        wire:click="prevPage" @disabled($page<=1)
                        class="inline-flex items-center px-1 py-0.5 bg-white border border-blue-600 text-blue-600 rounded-md font-semibold text-[0.5rem]
                        uppercase tracking-widest hover:bg-blue-600 hover:text-white active:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300
                        disabled:opacity-25 transition ease-in-out duration-150">
                        &larr; Previous
                    </button>

                    <span class="mt-2 inline-flex items-center px-1 py-0.5 bg-white text-blue-600 font-semibold text-[0.85rem]
                           uppercase tracking-widest hover:bg-blue-600 hover:text-white active:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300
                           disabled:opacity-25 transition ease-in-out duration-150">Page {{ $page }} of {{ $totalPages }}</span>

                    <button
                        type="button"
                        wire:click="nextPage" @disabled($page>=$totalPages)
                        class="inline-flex items-center px-1 py-0.5 bg-white border border-blue-600 text-blue-600 rounded-md font-semibold text-[0.5rem]
                           uppercase tracking-widest hover:bg-blue-600 hover:text-white active:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300
                           disabled:opacity-25 transition ease-in-out duration-150">
                        Next &rarr;
                    </button>
                </div>
                <span class="mb-2 inline-flex items-center px-3 py-1.5 rounded-md border border-blue-200 bg-blue-50 text-blue-900 text-sm font-semibold
                            dark:border-blue-800 dark:bg-blue-950 dark:text-blue-100">
                    Total: {{ $totalOrgs }}
                </span>
                <div class="p-1 dark:bg-slate-800 dark:border-gray-700">
                    <button
                        type="button"
                        wire:click="saveToFile"
                        class="inline-flex items-center px-1 py-0.5 bg-white border border-green-600 text-green-600 rounded-md font-semibold text-[0.5rem]
                           uppercase tracking-widest hover:bg-green-600 hover:text-white active:bg-green-700 focus:outline-none focus:border-green-800 focus:ring ring-green-300
                           disabled:opacity-25 transition ease-in-out duration-150">
                        Save to file
                    </button>
                    <button type="button" wire:click="downloadFile"
                            class="inline-flex items-center px-1 py-0.5 bg-white border border-blue-600 text-blue-600 rounded-md font-semibold text-[0.5rem]
                           uppercase tracking-widest hover:bg-blue-600 hover:text-white active:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-red-300
                           disabled:opacity-25 transition ease-in-out duration-150">
                        Download
                    </button>
                    @error('download') <div>{{ $message }}</div> @enderror
                    <button
                        type="button"
                        wire:click="clearFunding"
                        class="inline-flex items-center px-1 py-0.5 bg-white border border-red-600 text-red-600 rounded-md font-semibold text-[0.5rem]
                           uppercase tracking-widest hover:bg-red-600 hover:text-white active:bg-red-700 focus:outline-none focus:border-red-800 focus:ring ring-red-300
                           disabled:opacity-25 transition ease-in-out duration-150">
                        Reset from file
                    </button>

                    @if (session()->has('message'))
                        <div class="mt-2 text-sm text-green-600">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>

                <div class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                    @if ($upload) Selected: {{ $upload->getClientOriginalName() }} @endif
                </div>
                <div class="flex items-center gap-3">
                    <input
                        type="file"
                        wire:model="upload"
                        accept=".xlsx,.xls,.csv"
                        class="block w-full text-sm text-gray-700
                               file:mr-3 file:py-2 file:px-3
                               file:rounded-md file:border file:border-blue-200
                               file:bg-blue-50 file:text-blue-900 file:font-semibold
                               hover:file:bg-blue-100
                               dark:text-gray-200
                               dark:file:border-blue-800 dark:file:bg-blue-950 dark:file:text-blue-100
                               dark:hover:file:bg-blue-900/30" />

                    <button
                        type="button"
                        wire:click="uploadFundingFile"
                        wire:loading.attr="disabled"
                        wire:target="upload,uploadFundingFile"
                        class="inline-flex items-center px-3 py-2 rounded-md border border-blue-200 bg-blue-50 text-blue-900 text-sm font-semibold
                               hover:bg-blue-100
                               disabled:opacity-60 disabled:cursor-not-allowed
                               dark:border-blue-800 dark:bg-blue-950 dark:text-blue-100
                               dark:hover:bg-blue-900/30">
                        <span wire:loading.remove wire:target="uploadFundingFile">Upload</span>
                        <span wire:loading wire:target="uploadFundingFile">Uploading…</span>
                    </button>
                </div>

                @error('upload')
                <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                @enderror

                @if (session()->has('message'))
                    <div class="mt-2 text-sm text-green-700 dark:text-green-400">
                        {{ session('message') }}
                    </div>
                @endif


            </div>
        </div>
    </div>
</div>

