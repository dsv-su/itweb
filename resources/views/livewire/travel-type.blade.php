<div wire:ignore>
    <div
        x-data="{
        tabSelected: @entangle('tabselected').live,
        tabId: $id('tabs'),
        hiddenInputValue: @entangle('inputvalue').live,
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.tabRepositionMarker(tabButton);
            this.updateHiddenInput();
        },
        tabRepositionMarker(tabButton){
            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
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

        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);"  class="relative w-full max-w-sm">
        <label for="paper" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __("Please enter a country from the list, or select 'Domestic' if your travel is within Sweden.") }}
        </label>

        <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-600 dark:text-gray-400 bg-gray-100 rounded-lg select-none dark:bg-gray-700 dark:border-gray-600">
            <button :id="tabId + '-1'" @click="tabButtonClicked($el);" type="button" role="tab" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__("International")}}</button>
            <button :id="tabId + '-2'" @click="tabButtonClicked($el);" type="button" role="tab" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__("Domestic")}}</button>

            <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                <div class="w-full h-full bg-white rounded-md shadow-sm dark:border dark:border-gray-200"></div>
            </div>

        </div>

         <div class="relative w-full mt-2">
             <div :id="tabId + '-content-1'" x-show="tabContentActive($el)" class="relative">
                 <!-- Tab Content 1 -->
                 <livewire:select2.country-select2 :country="$country" />

                @error('country')
                <p class="mt-3 text-sm leading-6 text-red-600" x-init="$el.closest('form').scrollIntoView()">{{$message}}</p>
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
