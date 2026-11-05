@if(isset($proposal) && isset($proposal->files) && count($proposal->files) > 0)

        <h3 class="mb-4 text-blue-600 font-semibold dark:font-medium dark:text-white">Uploaded files!</h3>
        <!-- File card -->
        <div class="mb-2 min-w-0 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        @foreach($proposal->files as $key => $pp_file)
            <!-- Body -->
                <div class="min-w-0 border-b border-gray-200 p-4 last:border-b-0 md:p-5 dark:border-neutral-700">
                    <div>
                        <!-- Uploading File Content -->
                        <div class="flex min-w-0 flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                            <div class="flex min-w-0 flex-1 items-start gap-3">
                              <span class="inline-flex shrink-0 flex-col items-center gap-1 px-2 py-2 text-xs
                                    @if($pp_file['type'] == 'draft') text-yellow-800
                                    @elseif($pp_file['type'] == 'budget') text-purple-600
                                    @elseif($pp_file['type'] == 'final') text-blue-600
                                    @elseif($pp_file['type'] == 'decision') text-green-700
                                    @else text-gray-500
                                    @endif
                                    border
                                    @if($pp_file['type'] == 'draft') border-yellow-600
                                    @elseif($pp_file['type'] == 'budget') border-purple-600
                                    @elseif($pp_file['type'] == 'final') border-blue-600
                                    @elseif($pp_file['type'] == 'decision') border-green-600
                                    @else  border-gray-200
                                    @endif

                                    rounded-lg text-center dark:border-neutral-700 dark:text-neutral-300">
                                  <svg class="shrink-0 size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                      <polyline points="17 8 12 3 7 8"></polyline>
                                      <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg>
                                  {{ strtoupper($pp_file['type']) }}
                              </span>
                                <div class="min-w-0 flex-1 space-y-2">
                                    <p class="text-sm font-semibold text-gray-800 [overflow-wrap:anywhere] dark:text-white">{{$key}}</p>
                                    <dl class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-neutral-400">
                                        <div class="flex flex-wrap gap-x-1">
                                            <dt>Size:</dt>
                                            <dd>{{$pp_file['size']}} KB</dd>
                                        </div>
                                        <div class="flex min-w-0 flex-wrap gap-x-1">
                                            <dt>Date:</dt>
                                            <dd class="[overflow-wrap:anywhere]">{{$pp_file['date']}}</dd>
                                        </div>
                                        <div class="min-w-0 basis-full [overflow-wrap:anywhere]">
                                            <dt class="inline">Uploaded by:</dt>
                                            <dd class="inline">{{$pp_file['uploader']}}</dd>
                                        </div>
                                    </dl>
                                    <span class="inline-block max-w-full rounded border border-blue-400 bg-blue-100 px-2 py-1 text-[10px] font-medium text-blue-800 [overflow-wrap:anywhere] dark:bg-gray-700 dark:text-blue-200">
                                        {{strtoupper($pp_file['review'])}}
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full shrink-0 flex-wrap items-center gap-2 xl:w-auto">
                                @if($allow)
                                    <button wire:click.prevent="removefile('{{$key}}')"
                                            wire:confirm="Delete this file? This cannot be undone."
                                            aria-label="Delete {{ $key }}"
                                            type="button"
                                            class="inline-flex min-h-11 min-w-11 items-center justify-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:focus-visible:outline-red-300 disabled:opacity-50 disabled:pointer-events-none dark:border-red-400/40 dark:text-red-400 dark:hover:bg-red-900/30">
                                        <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                @endif
                                <button wire:click.prevent="downloadfile('{{$key}}')"
                                        aria-label="Download {{ $key }}"
                                        type="button"
                                        class="inline-flex min-h-11 min-w-11 items-center justify-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-300 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-400/40 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">
                                    <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 13V4"></path>
                                        <path d="M8 14l4 4 4-4"></path>
                                        <path d="M5 20h14"></path>
                                    </svg>
                                    <span>Download</span>
                                </button>



                            </div>
                        </div>
                        <!-- End Uploading File Content -->
                    </div>
                </div>
                <!-- End Body -->
        @endforeach
        <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 rounded-b-xl py-3 px-4 md:px-5 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                    <div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">
                      {{count($proposal->files)}} files
                    </span>
                    </div>
                    <!-- End Col -->

                    <div class="flex flex-wrap items-center gap-2">
                        @if($allow)
                            <button wire:click.prevent="removefolder()"
                                    wire:confirm="Delete all uploaded files? This cannot be undone."
                                    type="button"
                                    class="inline-flex min-h-11 min-w-11 items-center justify-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:focus-visible:outline-red-300 disabled:opacity-50 disabled:pointer-events-none dark:border-red-400/40 dark:text-red-400 dark:hover:bg-red-900/30">
                                <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                class="inline-flex min-h-11 min-w-11 items-center justify-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-300 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-400/40 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">
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
