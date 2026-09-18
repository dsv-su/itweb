<div wire:ignore class="travel-choice min-w-0">
    <div
        x-data="{
        tabSelected: @entangle('tabselected').live,
        tabId: $id('tabs'),
        hiddenInputValue: @entangle('inputvalue').live,
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.updateHiddenInput();
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        },
        updateHiddenInput(){
            if (this.tabSelected == 2 ) {
                this.hiddenInputValue = 'domestic';
                this.emitToLivewire();
            } else {
                this.hiddenInputValue = '';
            }
        },
        emitToLivewire(){
            $wire.selectedCountry(999);
        }
    }"

        class="travel-choice-layout relative w-full min-w-0">
        <p id="travel-type-choice-label" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __("Please enter a country from the list, or select 'Domestic' if your travel is within Sweden.") }}
        </p>

        <div role="group" aria-labelledby="travel-type-choice-label" x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full min-h-10 grid-cols-2 p-1 text-gray-600 dark:text-gray-200 bg-gray-100 rounded-lg select-none dark:bg-gray-700 dark:border-gray-600">
            <button :id="tabId + '-1'" :class="{ 'bg-white shadow-sm dark:bg-gray-900 dark:ring-1 dark:ring-gray-200': tabSelected == 1 }" :aria-pressed="(tabSelected == 1).toString()" :aria-controls="tabId + '-content-1'" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full min-h-8 px-2 py-1 text-sm font-medium transition-all rounded-md cursor-pointer break-words">{{__("International")}}</button>
            <button :id="tabId + '-2'" :class="{ 'bg-white shadow-sm dark:bg-gray-900 dark:ring-1 dark:ring-gray-200': tabSelected == 2 }" :aria-pressed="(tabSelected == 2).toString()" :aria-controls="tabId + '-content-2'" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full min-h-8 px-2 py-1 text-sm font-medium transition-all rounded-md cursor-pointer break-words">{{__("Domestic")}}</button>


        </div>

         <div class="relative w-full mt-2">
             <div :id="tabId + '-content-1'" x-show="tabContentActive($el)" class="relative">
                 <!-- Tab Content 1 -->
                 <livewire:select2.country-select2 :country="$country" />

                @error('country')
                <p id="country-error" class="mt-3 text-sm leading-6 text-red-700 dark:text-red-400" x-init="$el.closest('form').scrollIntoView()">{{$message}}</p>
                @enderror
                <!-- End Tab Content 1 -->

            </div>
            <div :id="tabId + '-content-2'" x-show="tabContentActive($el)" class="relative" x-cloak>
                <!-- Tab Content 2 -->
                <input type="hidden" name="countrytype" :value="hiddenInputValue">
                <!-- End Tab Content 2 -->
            </div>

        </div>

    </div>

</div>
