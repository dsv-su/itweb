<div>
    <div
        x-data="{
        tabSelected: 1,
        tabId: $id('tabs'),
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.tabRepositionMarker(tabButton);
        },
        tabRepositionMarker(tabButton){
            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        }
    }"

        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative w-full max-w-sm">
        <label for="paper" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __("Please select 'No project' if your travel is not associated with any specific project.") }}
        </label>
        <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-600 dark:text-gray-400 bg-gray-100 rounded-lg select-none dark:bg-gray-700 dark:border-gray-600">
            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__("Project")}}</button>
            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__("No project")}}</button>
            <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                <div class="w-full h-full bg-white rounded-md shadow-sm dark:border dark:border-gray-200"></div>
            </div>
        </div>

        <div class="relative w-full mt-2">
            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
                <!-- Tab Content 1 -->
                @if($type == 'resume')
                    <livewire:select2.project-select2 :id="$tr->project" />
                @else
                    <livewire:select2.project-select2 />
                @endif
                    <!-- End Tab Content 1 -->
            </div>

            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)"  class="relative" x-cloak>
                <!-- Tab Content 2 - Replace with your content -->

                <!-- End Tab Content 2 -->
            </div>

        </div>
    </div>
</div>

