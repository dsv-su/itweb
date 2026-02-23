@if(isset($proposal) && isset($proposal->files) && count($proposal->files) > 0)

        <h3 class="mb-4 text-blue-600 font-semibold dark:font-medium dark:text-white">Uploaded files!</h3>
        <!-- File card -->
        <div class="mb-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        @foreach($proposal->files as $key => $pp_file)
            <!-- Body -->
                <div class="p-4 md:p-5 space-y-7">
                    <div>
                        <!-- Uploading File Content -->
                        <div class="mb-2 flex justify-between items-center">
                            <div class="flex items-center gap-x-3">
                              <span class="inline-block px-2 py-1 text-xs
                                    @if($pp_file['type'] == 'draft') text-yellow-600
                                    @elseif($pp_file['type'] == 'budget') text-purple-600
                                    @elseif($pp_file['type'] == 'final') text-blue-600
                                    @elseif($pp_file['type'] == 'decision') text-green-600
                                    @else text-gray-500
                                    @endif
                                    border
                                    @if($pp_file['type'] == 'draft') border-yellow-600
                                    @elseif($pp_file['type'] == 'budget') border-purple-600
                                    @elseif($pp_file['type'] == 'final') border-blue-600
                                    @elseif($pp_file['type'] == 'decision') border-green-600
                                    @else  border-gray-200
                                    @endif

                                    rounded-lg text-center dark:border-neutral-700 dark:text-neutral-500">
                                  <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                      <polyline points="17 8 12 3 7 8"></polyline>
                                      <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg>
                                  {{ strtoupper($pp_file['type']) }}
                              </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{$key}}</p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-500">
                                        {{$pp_file['size']}} KB | Date: {{$pp_file['date']}} | Uploaded by: {{$pp_file['uploader']}} |
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-medium me-1 px-1 py-0 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 leading-none">
                                            {{strtoupper($pp_file['review'])}}
                                        </span>

                                    </p>
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-x-2">
                                @if($allow)
                                    <button wire:click.prevent="removefile('{{$key}}')"
                                            type="button"
                                            class="relative text-red-600 hover:text-red-800 focus:outline-none focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none dark:text-red-400 dark:hover:text-red-500 dark:focus:text-red-500">
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                        </svg>
                                        <span class="sr-only">Delete</span>
                                    </button>
                                @endif
                                <button wire:click.prevent="downloadfile('{{$key}}')"
                                        type="button"
                                        class="relative text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:text-blue-500 dark:focus:text-blue-500 px-4 py-2 text-base">
                                    <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 13V4"></path>
                                        <path d="M8 14l4 4 4-4"></path>
                                        <path d="M5 20h14"></path>
                                    </svg>
                                    <span class="sr-only">Download All</span>
                                </button>



                            </div>
                        </div>
                        <!-- End Uploading File Content -->
                    </div>
                </div>
                <!-- End Body -->
        @endforeach
        <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 rounded-b-xl py-2 px-4 md:px-5 dark:bg-white/10 dark:border-neutral-700">
                <div class="flex flex-wrap justify-between items-center gap-x-3">
                    <div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">
                      {{count($proposal->files)}} files
                    </span>
                    </div>
                    <!-- End Col -->

                    <div class="-me-2.5">
                        @if($allow)
                            <button wire:click.prevent="removefolder()"
                                    type="button"
                                    class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-transparent text-red-600 hover:bg-red-100 hover:text-red-800 focus:outline-none focus:bg-red-100 focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none dark:text-red-400 dark:hover:bg-red-900 dark:hover:text-red-300 dark:focus:bg-red-900 dark:focus:text-red-300">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                                Delete All
                            </button>
                        @endif
                        <button wire:click.prevent="downloadfolder()"
                                type="button"
                                class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-transparent text-blue-600 hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:bg-blue-100 focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:hover:text-blue-300 dark:focus:bg-blue-900 dark:focus:text-blue-300">
                            <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 13V4"></path>
                                <path d="M8 14l4 4 4-4"></path>
                                <path d="M5 20h14"></path>
                            </svg>
                            Download All
                        </button>

                    </div>
                    <!-- End Col -->
                </div>
            </div>
            <!-- End Footer -->
        </div>
        <!-- End File Uploading Progress Form -->



@else
    <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("No attachments have been uploaded") }}</div>
@endif
