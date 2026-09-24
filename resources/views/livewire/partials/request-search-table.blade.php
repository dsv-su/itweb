    <div class="relative w-full min-w-0 max-w-full xl:overflow-x-auto xl:rounded-lg">
        <table role="table" class="block xl:table xl:min-w-[70rem] w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead role="rowgroup" class="hidden xl:table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
            <tr>
                <th scope="col" class="px-3 py-3">{{__("Request type")}}</th>
                <th scope="col" class="px-3 py-3">{{__("Name")}}</th>
                <th scope="col" class="px-3 py-3">{{__("Country")}}</th>
                <th scope="col" class="px-3 py-3">{{__("Total")}}</th>
                <th scope="col" class="px-3 py-3">{{__("State")}}</th>
                <th scope="col" class="px-3 py-3">{{__("User")}}</th>
                <th scope="col" class="px-3 py-3">{{ __('Financial officer') }}</th>
                <th scope="col" class="px-3 py-3">{{__("Created")}}</th>
                <th scope="col" class="w-52 px-3 py-3">{{__("Action")}}</th>
            </tr>
            </thead>
            <tbody role="rowgroup" class="block space-y-4 xl:table-row-group xl:space-y-0">
            @foreach($dashboards as $dashboard)

                <tr role="row" wire:key="request-{{ $dashboard->id }}" class="grid grid-cols-2 overflow-hidden rounded-xl border shadow-sm xl:table-row xl:rounded-none xl:border-0 xl:border-b xl:shadow-none bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 dark:text-white">

                    <th role="rowheader" scope="row" class="col-span-2 block px-3 py-3 text-xs text-gray-900 xl:table-cell xl:align-top dark:text-white">
                        <span class="bg-blue-100 inline-block text-xs px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-white border border-blue-400">
                            {{ $dashboard->type === 'travelrequest' ? __('Travelrequest') : __(ucfirst(str_replace('_', ' ', (string) $dashboard->type))) }}
                        </span>
                    </th>
                    <td role="cell" class="col-span-2 block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('Name') }}</span>{{$dashboard->name}}</td>
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('Country') }}</span>{{ $dashboard->travel?->country ?? '-' }}</td>
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('Total') }}</span>
                        @if($dashboard->travel?->total !== null)
                            {{ number_format((float) $dashboard->travel->total, 0, '.', ' ') }} SEK
                        @else
                            -
                        @endif
                    </td>
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs xl:whitespace-nowrap">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('State') }}</span>
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
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('User') }}</span>{{ $dashboard->user?->name ?? __('Unknown user') }}</td>
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('FO') }}</span>{{ $dashboard->financialOfficer?->name ?? __('Unassigned') }}</td>
                    <td role="cell" class="block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3 text-xs xl:whitespace-nowrap">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('Created') }}</span>{{\Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateString()}}</td>
                    <td role="cell" class="col-span-2 block min-w-0 break-words xl:table-cell xl:align-top px-3 py-3">
                        <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400 xl:hidden">{{ __('Action') }}</span>
                        <div class="flex w-full flex-wrap gap-2 xl:w-44 xl:flex-col [&>*]:flex-auto [&>*]:whitespace-nowrap [&>a]:min-h-11 [&>button]:min-h-11 xl:[&>a]:min-h-0 xl:[&>button]:min-h-0">
                        <a type="button" href="{{route('fo-request-show', $dashboard->request_id)}}"
                           class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-offset-gray-800">
                            {{__("Show")}}
                        </a>
                        @if($dashboard->type === 'travelrequest' && (string) $dashboard->state === 'head_approved')
                            <a href="{{ route('travel-request-review', $dashboard->id) }}"
                               class="inline-flex items-center justify-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800">
                                {{ __('Review') }}
                            </a>
                        @endif
                        @if($canEditCompleted && (string) $dashboard->state === 'fo_approved' && $dashboard->type === 'travelrequest')
                            <a href="{{ route('travel-request-edit-completed', $dashboard->request_id) }}"
                               class="inline-flex items-center justify-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800">
                                {{ __('Edit') }}
                            </a>
                        @endif
                        @if($canEditCompleted && $dashboard->type === 'travelrequest')
                            <button type="button" wire:click="switchFo({{ $dashboard->id }})"
                                    aria-expanded="{{ $switchingFoId === $dashboard->id ? 'true' : 'false' }}"
                                    aria-controls="switch-fo-{{ $dashboard->id }}"
                                    class="inline-flex items-center justify-center rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-blue-500 dark:text-blue-300 dark:hover:bg-gray-700">
                                {{ __('Switch FO') }}
                            </button>
                        @endif
                        @if($canEditCompleted && $dashboard->type === 'travelrequest' && $dashboard->travel)
                            <button type="button"
                                    wire:click="setReminders({{ $dashboard->id }}, {{ $dashboard->travel->reminder ? 'false' : 'true' }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center justify-center rounded-md border px-3 py-1.5 text-xs font-medium transition focus:outline-none focus:ring-2 disabled:opacity-50 {{ $dashboard->travel->reminder ? 'border-gray-300 text-gray-700 hover:bg-gray-100 focus:ring-gray-400 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700' : 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100 focus:ring-amber-500 dark:border-amber-500 dark:bg-amber-900/30 dark:text-amber-200 dark:hover:bg-amber-900/50' }}">
                                {{ $dashboard->travel->reminder ? __('Disable reminders') : __('Enable reminders') }}
                            </button>
                        @endif
                        @if($dashboard->state == 'fo_approved' && $dashboard->type == 'travelrequest')
                            <a type="button" href="{{route('travel-request-pdf', $dashboard->request_id)}}"
                               class="inline-flex items-center justify-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                                            rounded-md text-xs px-3 py-1.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white
                                            dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                {{__("Download")}}
                            </a>
                        @elseif(in_array($dashboard->state, ['manager_denied', 'head_denied', 'fo_denied']) && $dashboard->type == 'travelrequest')
                            <a type="button" href="{{route('travel-request-pdf', $dashboard->request_id)}}"
                               class="inline-flex items-center justify-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                                            rounded-md text-xs px-3 py-1.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white
                                            dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                {{__("Download")}}
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center rounded-md border border-yellow-300 bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 dark:border-yellow-500/40 dark:bg-yellow-500/10 dark:text-yellow-300">
                                {{__("Processing")}}
                            </span>


                        @endif
                        </div>
                    </td>
                </tr>
                @if($canEditCompleted && $switchingFoId === $dashboard->id && $dashboard->type === 'travelrequest')
                    <tr wire:key="switch-fo-{{ $dashboard->id }}" id="switch-fo-{{ $dashboard->id }}" role="row" class="block rounded-xl border border-blue-100 bg-blue-50/60 xl:table-row xl:rounded-none xl:border-0 xl:border-b dark:border-gray-700 dark:bg-gray-900">
                        <td role="cell" colspan="9" class="block px-4 py-4 xl:table-cell">
                            <form wire:submit="saveFo" class="flex flex-wrap items-end gap-3">
                                <div class="w-full min-w-0 sm:w-72">
                                    <label for="new-fo-{{ $dashboard->id }}" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">{{ __('Financial officer') }}</label>
                                    <select id="new-fo-{{ $dashboard->id }}" wire:model="selectedFoId" class="block w-full rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                        <option value="">{{ __('Choose a financial officer') }}</option>
                                        @foreach($financialOfficers as $officer)
                                            <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedFoId') <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <button type="submit" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50">{{ __('Save') }}</button>
                                <button type="button" wire:click="cancelFoSwitch" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">{{ __('Cancel') }}</button>
                                <p class="w-full text-xs text-gray-600 dark:text-gray-400">{{ __('The change will be recorded in the travel request FO history.') }}</p>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach

            </tbody>
        </table>

    </div>
