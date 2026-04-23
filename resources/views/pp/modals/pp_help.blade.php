<!-- Title modal -->
<div id="title-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="title-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold">{{ __("Project proposal Title") }}</h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="title-modal">
                    <!-- icon -->
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Add a title for your Project proposal. This will be shown on your dashboard.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="title-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Description modal -->
<div id="objective-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="objective-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        Objective of your proposal
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="objective-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Write a short summary of the goals of the research..")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="objective-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Principal Investigation modal -->
<div id="principal_investigator-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="principal_investigator-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Principal investigator at DSV")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="principal_investigator-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("It is assumed that you are the lead investigator of this project from the DSV side. In case the main PI is from another institution please indicate that below.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="principal_investigator-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Principal Investigation email modal -->
<div id="principal_investigator_email-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="principal_investigator_email-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Principal investigator email")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="principal_investigator_email-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("It is assumed that you are the lead investigator. Email for sending you notifications.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="principal_investigator_email-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Co Investigation modal -->
<div id="coinvestigators-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="coinvestigators-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Co-investigators")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="coinvestigators-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{__("Search for and add your co-investigators. This search allows you to find colleagues within Stockholm University (SUKAT), and you can also search using an email address. If your co-investigators are outside SU, you can add them manually by clicking Add+ and entering their name and email address.")}}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="coinvestigators-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Research area modal -->
<div id="research_area-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="research_area-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Research subject")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="research_area-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Add the proposals research subject.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="research_area-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Unit head modal -->
<div id="unithead-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="unithead-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Unit Head to approve your proposal")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="unithead-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Select the Unit head to approve your proposal")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="unithead-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- DSV coordination modal -->
<div id="dsvcoordinationg-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="dsvcoordinationg-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("DSV coordination")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="dsvcoordinationg-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Is DSV coordinating the research? If 'No' enter the other coordinator.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="dsvcoordinationg-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- EU modal -->
<div id="eu-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="eu-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Eu Project")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="eu-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Is this a proposal for an EU project? If so, a confirmation from SU Research Services will be required.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="eu-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Wallenberg modal -->
<div id="eu_wallenberg-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="eu_wallenberg-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Wallenberg Project")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="eu_wallenberg-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Is this a proposal for a Wallenberg project? If so, a confirmation from SU Research Services will be required.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="eu_wallenberg-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Funding organization modal -->
<div id="funding_organization-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="funding_organization-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Funding Agency")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="funding_organization-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Select the funding Agency.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="funding_organization-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd">
                        </path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Program/Call/target modal -->
<div id="program-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="program-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Program/Call/Target")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="program-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the program call link.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="program-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Decision expected modal -->
<div id="decision_exp-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="decision_exp-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Decision expeted")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="decision_exp-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the excepted decision date.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="decision_exp-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Start date expected modal -->
<div id="start_date-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="start_date-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Start date")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="start_date-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the start date.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="start_date-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Submission deadline modal -->
<div id="submission-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="submission-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Submission deadline")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="submission-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the date of the submission deadline")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="submission-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Project duration modal -->
<div id="duration-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="duration-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Project duration in months")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="duration-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the project duration. Project duration should be indicated in months.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="duration-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Complete Budget modal -->
<div id="budget_project-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="budget_project-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Project budget")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="budget_project-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the budget for the entire project.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="budget_project-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- DSV Budget modal -->
<div id="budget_dsv-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="budget_dsv-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("DSV budget")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="budget_dsv-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the budget for DSV.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="budget_dsv-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- DSV Budget modal -->
<div id="budget_phd-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="budget_phd-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Years of PhD co-financing needed")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="budget_phd-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Please enter the requested years for the PhD co-financing, or 0 if none")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="budget_phd-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Currency modal -->
<div id="currency-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="currency-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Currency")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="currency-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the currency in SEK, Us dollars or Euro.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="currency-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Co financing modal -->
<div id="cofinancing-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="cofinancing-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Co-financing")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="cofinancing-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("To support the Vice’s decision on how to proceed with this project, this note explains the reasoning behind the proposed co-financing arrangement.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="cofinancing-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- OH cost modal -->
<div id="oh_cost-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="oh_cost-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Percent of OH cost covered")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="oh_cost-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the percentage of the total project costs that corresponds to covered overhead (OH) costs, not the percentage of OH costs covered.
    For example, if the funding agency covers all overhead costs, and overhead corresponds to 46% of total costs, you should enter 46%, not 100%.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="oh_cost-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Premises cost modal -->
<div id="premises-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="premises-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Percent of premises cost covered")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="premises-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the percentage of the premises costs covered. The default premises overhead for DSV for this year is pre-set.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="premises-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cofinancing needed modal -->
<div id="cofinancing_needed-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="cofinancing_needed-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Amount of co-financing needed")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="cofinancing_needed-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Enter the budget for the co-financing needed.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="cofinancing_needed-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Comments modal -->
<div id="user_comments-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="user_comments-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Comments")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="user_comments-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("Please provide your comments .")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="user_comments-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Upload modal -->
<div id="upload-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="upload-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{__("Proposal attachments")}}
                    </h3>

                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="upload-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("Instructions")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("All files that belong to the proposal should be added here. This is mandatory that you should upload the proposal's budget once you have it and a short description file of the proposal.")}}</dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{__("More help?")}}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{__("If you need more help contact helpdesk@dsv.su.se")}}</dd>
            </dl>
            <div class="flex justify-between items-center">

                <button type="button" data-modal-toggle="upload-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                                                rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{__("Close")}}
                </button>
            </div>
        </div>
    </div>
</div>
