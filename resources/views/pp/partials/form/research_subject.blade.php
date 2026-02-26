<div class="w-full sm:col-span-2">
    <label for="research_area" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Research subject") }}<span class="text-red-600"> *</span>
        <button id="research_area-button" data-modal-toggle="research_area-modal" class="inline" type="button">
            <svg class="w-[16px] h-[16px] inline text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    </label>

    @if(in_array($type, ['preapproval', 'saved', 'edit', 'complete', 'resume']))
        <select id="research_area" name="research_area"
                class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
            @foreach($research_areas as $research_area)
                @if(in_array($type, ['edit', 'saved', 'resume']))
                    <option value="{{$research_area->name}}" @if($proposal->pp['research_area'] == $research_area->name) selected="" @endif >{{$research_area->name}}</option>
                @else
                    <option value="{{$research_area->name}}">{{$research_area->name}}</option>
                @endif
            @endforeach
        </select>
        @error('research_area')
        <p class="mt-3 text-sm leading-6 text-red-600">{{__("This is a required input")}}</p>
        @enderror
    @else
        @include('pp.partials.review.research_area')
    @endif
</div>
