<div class="flex flex-col flex-1 w-full">
    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
    <div class="relative px-36 mb-6">
        <input type="search" id="request-search" wire:model.live="searchTerm"
               class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="{{__("Please refine your search by typing to filter ID, User, Purpose, or ProjectID")}}">
    </div>
    <!-- Pagination -->
    <nav class="flex justify-end items-center dark:text-white" aria-label="Pagination">
        {{ $dashboards->links() }}
    </nav>
    <!-- End Pagination -->

    <div class="mt-8 relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
            <tr>
                <th scope="col" class="px-4 py-3">{{__("Request type")}}</th>
                <th scope="col" class="px-4 py-3">{{__("Name")}}</th>
                <th scope="col" class="px-4 py-3">{{__("RequestId")}}</th>
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
                        {{--}}
                        @if($dashboard->type == 'travelrequest')
                            <span class="bg-blue-100 text-xs mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-white border border-blue-400">
                            {{__("Travelrequest")}}
                            </span>
                        @else
                            {{$dashboard->type}}
                        @endif
                        {{--}}
                        <span class="bg-blue-100 text-xs mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-white border border-blue-400">
                            {{$dashboard->type}}
                        </span>
                    </th>
                    <td class="px-4 py-3 text-xs">{{$dashboard->name}}</td>
                    <td class="px-4 py-3 text-xs">{{$dashboard->request_id}}</td>
                    <td class="px-4 py-3 text-xs">
                        @switch($dashboard->state)
                            @case('submitted')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                    {{__("Submitted")}}
                                </span>
                            @break
                            @case('manager_approved')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                    {{__("Processing")}}
                                </span>
                            @break
                            @case('manager_denied')
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">
                                    {{__("Denied")}}
                                </span>
                            @break
                            @case('manager_returned')
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                    {{__("Returned by manager")}}
                                </span>
                            @break
                            @case('fo_approved')
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
                                    {{__("Completed")}}
                                </span>
                            @break
                            @case('fo_denied')
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">
                                    {{__("Denied")}}
                                </span>
                            @break
                            @case('fo_returned')
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                    {{__("Returned")}}
                                </span>
                            @break
                            @case('head_approved')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                    {{__("Processing")}}
                                </span>
                            @break
                            @case('head_denied')
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">
                                    {{__("Denied")}}
                                </span>
                            @break
                            @case('head_returned')
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                    {{__("Returned")}}
                                </span>
                            @break
                        @endswitch
                    </td>
                    <td class="px-4 py-3 text-xs">{{\App\Models\User::find($dashboard->user_id)->name}}</td>
                    <td class="px-4 py-3 text-xs">{{\Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateString()}}</td>
                    <td>
                        <a type="button" href="{{route('fo-request-show', $dashboard->request_id)}}"
                           class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300
                                rounded-lg text-xs px-3 py-2 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">
                            {{__("Show")}}
                        </a>
                    </td>
                    <td>
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
                            <div class="mt-1 text-yellow-400 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                                        rounded-lg text-xs px-3 py-2 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white
                                        dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                {{__("Processing")}}
                            </div>


                        @endif


                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
</div>
