<div class="flex flex-wrap justify-between items-center">
    <div class="flex items-center">
        <livewire:indicator />
        <!-- Desktop -->
        <button data-tooltip-target="workflow-notification-tooltip" type="button" data-dropdown-toggle="notification-dropdown"
                class="hidden md:block p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
            <span class="sr-only">View notifications</span>
            <!-- Bell icon -->
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 21">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M8 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C15 15.4 15 16 14.462 16H1.538C1 16 1 15.4 1 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 8 3.464ZM4.54 16a3.48 3.48 0 0 0 6.92 0H4.54Z"/>
            </svg>
        </button>

        <!-- Dropdown menu -->
        <div class="hidden overflow-hidden z-50 my-4 w-full md:max-w-md text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700" id="notification-dropdown">
            <livewire:notificationstoggler />
            <div>
                <livewire:requestnotifications />
                <livewire:returnednotifications />
            </div>
            <livewire:userrequeststoggler />

            <div>
                <livewire:usernotifications />
            </div>

        </div>
        <!-- Requestforms -->
        <button data-tooltip-target="workflow-requests-tooltip" type="button" data-dropdown-toggle="apps-dropdown"
                class="hidden md:block p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
            <span class="sr-only">View notifications</span>
            <!-- Icon -->
            <svg class="w-[18px] h-[18px] text-gray-800 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857Zm10 0A1.857 1.857 0 0 0 13 14.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 19.143v-4.286A1.857 1.857 0 0 0 19.143 13h-4.286Z" clip-rule="evenodd"/>
            </svg>

        </button>
        <!-- Dropdown menu -->
        <div class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="apps-dropdown">
            <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                {{__("Available e-services")}}
            </div>
            <div class="grid grid-cols-3 gap-4 p-4">
                <!-- Travel request -->
                <a href="{{route('travel-request-create')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                    <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 group-hover:text-white dark:text-white dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9V4a3 3 0 0 0-6 0v5m9.92 10H2.08a1 1 0 0 1-1-1.077L2 6h14l.917 11.923A1 1 0 0 1 15.92 19Z"/>
                    </svg>
                    <div class="text-sm font-medium text-blue-600 dark:text-white">{{__("Travel Request")}}</div>
                </a>
                <!-- Project proposals -->
               @if(\App\Models\SettingsOh::first()->form_enable)
                <a href="{{route('pp.show', 'my')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                    <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h4M9 3v4a1 1 0 0 1-1 1H4m11 6v4m-2-2h4m3 0a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                    </svg>

                    <div class="text-sm font-medium text-blue-600 dark:text-white">Project Proposals</div>
                </a>
                @endif

                {{--}}
                <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                    <svg class="mx-auto mb-2 w-5 h-5 text-gray-900 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M1 17V2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a2 2 0 0 0-2 2Zm0 0a2 2 0 0 0 2 2h12M5 15V1m8 18v-4"/>
                    </svg>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">Book Request</div>
                </a>
                <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                    <svg class="mx-auto mb-2 w-5 h-5 text-gray-900 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 14H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v1M5 19h5m-9-9h5m4-4h8a1 1 0 0 1 1 1v12H9V7a1 1 0 0 1 1-1Zm6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                    </svg>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">Computer Request</div>
                </a>
               {{--}}
            </div>
        </div>
        <!-- FO -->
        @php
            //Financialofficers
            $roleIds = \Illuminate\Support\Facades\DB::table('group_user')->where('group_id', 'ekonomi')->pluck('user_id')->toArray();
            $financialOfficer = in_array(Illuminate\Support\Facades\Auth::user()->id, $roleIds);
            //UnitHeads
            $roleIds = \Illuminate\Support\Facades\DB::table('group_user')->where('group_id', 'enhetschef')->pluck('user_id')->toArray();
            $unitHead = in_array(Illuminate\Support\Facades\Auth::user()->id, $roleIds);
        @endphp

        {{--}}@if(\Statamic\Auth\User::current()->can('financial_officer')){{--}}
        @if($financialOfficer || $unitHead)
            <button data-tooltip-target="lists-requests-tooltip" type="button" data-dropdown-toggle="fo-dropdown"
                    class="hidden md:block p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                <span class="sr-only">View notifications</span>
                <!-- Icon -->
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="fo-dropdown">
                <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    {{__("User requests")}}
                </div>
                <div class="grid grid-cols-3 gap-4 p-4">
                    <!--Request list -->
                    <a href="{{route('request-list')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">

                        <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="20" fill="none" viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M12 2h4a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4m6 0v3H6V2m6 0a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M5 5h8m-5 5h5m-8 0h.01M5 14h.01M8 14h5"/>
                        </svg>
                        <div class="text-sm font-medium text-blue-600 dark:text-white">Request list</div>
                    </a>
                    <!--FO settings -->
                    <a href="{{route('settings')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">

                        <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2"/>
                        </svg>
                        <div class="text-sm font-medium text-blue-600 dark:text-white">{{__("Settings")}}</div>
                    </a>
                    <!-- Project proposals settings -->
                    <a href="{{route('vice_settings.index')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">

                        <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2"/>
                        </svg>
                        <div class="text-sm font-medium text-blue-600 dark:text-white">{{__("Project Proposal Settings")}}</div>
                    </a>

                </div>
            </div>
        <!-- end FO -->
            {{--}}
        @elseif(\Statamic\Auth\User::current()->can('unit_head'))
            <button data-tooltip-target="lists-requests-tooltip" type="button" data-dropdown-toggle="fo-dropdown"
                    class="hidden md:block p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                <span class="sr-only">View notifications</span>
                <!-- Icon -->
                <svg class="w-6 h-6 text-gray-800 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="fo-dropdown">
                <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    {{__("User requests")}}
                </div>
                <div class="grid grid-cols-3 gap-4 p-4">
                    <!--Request list -->
                    <a href="{{route('request-list')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">

                        <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="20" fill="none" viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M12 2h4a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4m6 0v3H6V2m6 0a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M5 5h8m-5 5h5m-8 0h.01M5 14h.01M8 14h5"/>
                        </svg>
                        <div class="text-sm font-medium text-blue-600 dark:text-white">Request list</div>
                    </a>
                    <!--FO settings -->
                    <a href="{{route('settings')}}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">

                        <svg class="mx-auto mb-2 w-5 h-5 text-blue-600 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2"/>
                        </svg>
                        <div class="text-sm font-medium text-blue-600 dark:text-white">{{__("Settings")}}</div>
                    </a>
                </div>
            </div>
            {{--}}
        @endif

    </div>
</div>
<!-- Tooltips -->
<div id="workflow-notification-tooltip" role="tooltip"
     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
     style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
     data-popper-placement="top">Notifications
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
<div id="workflow-requests-tooltip" role="tooltip"
     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
     style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
     data-popper-placement="top">E-forms
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
<div id="lists-requests-tooltip" role="tooltip"
     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
     style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(1443px, 692px);"
     data-popper-placement="top">List user requests
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
