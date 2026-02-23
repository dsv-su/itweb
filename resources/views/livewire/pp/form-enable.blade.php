<div>
    <p class="mt-1 text-gray-600 dark:text-gray-400">Project proposals</p>
    <div class="mt-5 space-y-4">
        <form action="{{ route('vice_settings.form') }}" method="POST">
            @csrf
            <div class="mt-3">
                <div class="border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
                    <ul class="max-w-sm flex flex-col">
                        <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="relative flex items-start w-full">
                                <!-- Form enable -->
                                <div @if($oh->form_enable) x-data="{ switchOn: true }" @else  x-data="{ switchOn: false }" @endif class="flex items-center justify-center space-x-2">
                                    <input id="thisId" type="checkbox" name="switch" class="hidden"  :checked="switchOn" >

                                    <button
                                        x-ref="switchButton"
                                        type="button"
                                        @click="switchOn = ! switchOn"
                                        wire:click="switchButton()"
                                        :class="switchOn ? 'bg-blue-600' : 'bg-neutral-200'"
                                        class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                        x-cloak>
                                        <span :class="switchOn ? 'translate-x-[18px]' : 'translate-x-0.5'" class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                                    </button>

                                    <label @click="$refs.switchButton.click(); $refs.switchButton.focus()" :id="$id('switch')"
                                           :class="{ 'text-blue-600': switchOn, 'text-gray-400': ! switchOn }"
                                           class="text-sm select-none"
                                           x-cloak>
                                        Enable Project Proposals
                                    </label>
                                </div>
                                <!-- end form enable -->
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </form>
    </div>

</div>
