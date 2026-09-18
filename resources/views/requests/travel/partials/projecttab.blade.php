<div class="travel-choice min-w-0">
    <div
        x-data="{
        tabSelected: 1,
        tabId: $id('tabs'),
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        }
    }"

        class="travel-choice-layout relative w-full min-w-0">
        <p id="project-choice-label" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __("Select the project for this trip. If you cannot select a project, choose 'No project' and explain why in Comments.") }}
        </p>
        <div role="group" aria-labelledby="project-choice-label" x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full min-h-10 grid-cols-2 p-1 text-gray-600 dark:text-gray-200 bg-gray-100 rounded-lg select-none dark:bg-gray-700 dark:border-gray-600">
            <button :id="tabId + '-1'" :class="{ 'bg-white shadow-sm dark:bg-gray-900 dark:ring-1 dark:ring-gray-200': tabSelected == 1 }" :aria-pressed="(tabSelected == 1).toString()" :aria-controls="tabId + '-content-1'" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full min-h-8 px-2 py-1 text-sm font-medium transition-all rounded-md cursor-pointer break-words">{{__("Project")}}</button>
            <button :id="tabId + '-2'" :class="{ 'bg-white shadow-sm dark:bg-gray-900 dark:ring-1 dark:ring-gray-200': tabSelected == 2 }" :aria-pressed="(tabSelected == 2).toString()" :aria-controls="tabId + '-content-2'" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full min-h-8 px-2 py-1 text-sm font-medium transition-all rounded-md cursor-pointer break-words">{{__("No project")}}</button>
        </div>

        <div class="relative w-full mt-2">
            <div :id="tabId + '-content-1'" x-show="tabContentActive($el)" class="relative">
                <fieldset :disabled="tabSelected == 2" class="min-w-0">
                    <livewire:select2.project-select2
                        :id="old('project', $travelRequest?->project ?? 0)"
                        :show-project-warning="true"
                    />
                </fieldset>
            </div>

            <div :id="tabId + '-content-2'" x-show="tabContentActive($el)"  class="relative" x-cloak>
                <input type="hidden" name="project" value="" :disabled="tabSelected != 2">
                @include('requests.travel.partials.form.project-warning', ['warningId' => 'project-none-warning'])
            </div>

        </div>
    </div>
</div>

