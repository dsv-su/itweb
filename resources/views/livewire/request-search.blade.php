<div class="flex flex-col flex-1 w-full">
    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
    <div class="relative px-36 mb-6">
        <input type="search" id="request-search" wire:model.live="searchTerm"
               class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="{{__("Please refine your search by typing to filter ID, User, Country, Purpose, or ProjectID")}}">
    </div>
    <div class="mb-6 flex justify-center">
        <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1 shadow-sm dark:border-gray-700 dark:bg-gray-800" role="group" aria-label="{{ __('Request type filter') }}">
            <button
                type="button"
                wire:click="$set('requestType', 'travelrequest')"
                wire:loading.attr="disabled"
                class="rounded-md px-4 py-2 text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $requestType === 'travelrequest' ? 'bg-blue-600 text-white shadow-sm dark:bg-blue-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}"
            >
                {{ __('Travelrequest') }}
            </button>
            <button
                type="button"
                wire:click="$set('requestType', 'projectproposal')"
                wire:loading.attr="disabled"
                class="rounded-md px-4 py-2 text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $requestType === 'projectproposal' ? 'bg-blue-600 text-white shadow-sm dark:bg-blue-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}"
            >
                {{ __('Projectproposal') }}
            </button>
        </div>
    </div>
    @if ($dashboards->hasPages())
        <div class="mb-6">
            {{ $dashboards->onEachSide(1)->links('livewire.partials.request-search-pagination') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
            <tr>
                <th scope="col" class="px-4 py-3">{{__("Request type")}}</th>
                <th scope="col" class="px-4 py-3">{{__("Name")}}</th>
                <th scope="col" class="px-4 py-3">{{__("Country")}}</th>
                <th scope="col" class="px-4 py-3">{{__("Total")}}</th>
                <th scope="col" class="px-4 py-3">{{__("State")}}</th>
                <th scope="col" class="px-4 py-3">{{__("User")}}</th>
                <th scope="col" class="px-4 py-3">{{__("Created")}}</th>
                <th scope="col" colspan="2" class="px-4 py-3">{{__("Action")}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dashboards as $dashboard)

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 dark:text-white">

                    <th scope="row" class="px-4 py-3 text-xs text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="bg-blue-100 text-xs mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-white border border-blue-400">
                            {{ $dashboard->type === 'travelrequest' ? __('Travelrequest') : ucfirst(str_replace('_', ' ', (string) $dashboard->type)) }}
                        </span>
                    </th>
                    <td class="px-4 py-3 text-xs">{{$dashboard->name}}</td>
                    <td class="px-4 py-3 text-xs">{{ $dashboard->travel?->country ?? '-' }}</td>
                    <td class="px-4 py-3 text-xs">
                        @if($dashboard->travel?->total !== null)
                            {{ number_format((float) $dashboard->travel->total, 0, '.', ' ') }} SEK
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs whitespace-nowrap">
                        @php
                            $state = (string) $dashboard->state;
                            $stateLabel = match ($state) {
                                'submitted' => __('Submitted'),
                                'manager_approved', 'head_approved' => __('Processing'),
                                'manager_denied', 'head_denied', 'fo_denied' => __('Denied'),
                                'manager_returned' => __('Returned by manager'),
                                'fo_returned', 'head_returned' => __('Returned'),
                                'fo_approved' => __('Completed'),
                                'final_approved' => __('Final Approved'),
                                default => __(ucwords(str_replace('_', ' ', $state))),
                            };
                            $stateClass = match ($state) {
                                'fo_approved', 'final_approved' => 'border-green-200 bg-green-50 text-green-700 dark:border-green-500/40 dark:bg-green-500/10 dark:text-green-300',
                                'manager_denied', 'head_denied', 'fo_denied' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-300',
                                'manager_returned', 'fo_returned', 'head_returned' => 'border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-500/40 dark:bg-gray-500/10 dark:text-gray-300',
                                default => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-500/40 dark:bg-blue-500/10 dark:text-blue-300',
                            };
                        @endphp

                        <span class="inline-flex min-w-[7.75rem] items-center justify-center rounded-md border px-3 py-1.5 text-xs font-medium leading-4 {{ $stateClass }}">
                            {{ $stateLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $dashboard->user?->name ?? __('Unknown user') }}</td>
                    <td class="px-4 py-3 text-xs">{{\Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateString()}}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a type="button" href="{{route('fo-request-show', $dashboard->request_id)}}"
                           class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-offset-gray-800">
                            {{__("Show")}}
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($dashboard->state == 'fo_approved' && $dashboard->type == 'travelrequest')
                            <a type="button" href="{{route('travel-request-pdf', $dashboard->request_id)}}"
                               class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                                            rounded-lg text-xs px-3 py-2 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white
                                            dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                {{__("Download")}}
                            </a>
                        @elseif(in_array($dashboard->state, ['manager_denied', 'head_denied', 'fo_denied']) && $dashboard->type == 'travelrequest')
                            <a type="button" href="{{route('travel-request-pdf', $dashboard->request_id)}}"
                               class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                                            rounded-lg text-xs px-3 py-2 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white
                                            dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                {{__("Download")}}
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center rounded-md border border-yellow-300 bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 dark:border-yellow-500/40 dark:bg-yellow-500/10 dark:text-yellow-300">
                                {{__("Processing")}}
                            </span>


                        @endif


                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
</div>
