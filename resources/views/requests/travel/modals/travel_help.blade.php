<!-- Name modal -->
<div id="name-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="name-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{ __("Duty Travel Request Name") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="name-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Add a short name for your Travel Request. This will be shown on your dashboard.") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="name-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Purpose modal -->
<div id="purpose-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="purpose-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        Duty Travel Request Purpose
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="purpose-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Purpose of the mission with the web address of the conference or event.") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="purpose-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Project modal -->
<div id="project-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="project-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{ __("Duty Travel Request Project") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="project-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Select a project id from the dropdown list or search for the project in the search") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="project-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Country modal -->
<div id="country-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="country-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{ __("Duty Travel Request Country") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="country-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Please select the country you'd like to visit as your travel destination.") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="country-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Project leader modal -->
<div id="projectleader-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="projectleader-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{ __("Duty Travel Request Projectleader or Manager") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="projectleader-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Please choose the project leader or manager for your project from the list. If your project does not have a designated leader or you are working independently, please select your own name.") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="projectleader-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Unithead modal -->
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
                        {{ __("Duty Travel Request Unit Head") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="unithead-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Please select the unit head who is responsible for the project.") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="unithead-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Paper accepted modal -->
<div id="paper-modal"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden"
>
    <!-- Backdrop (below the modal) -->
    <div class="fixed inset-0 bg-black/50 z-0" data-modal-toggle="paper-modal"></div>

    <!-- Modal wrapper (above the backdrop) -->
    <div class="relative z-10 p-4 w-full max-w-xl">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                    <h3 class="font-semibold ">
                        {{ __("Duty Travel Request Paper accepted") }}
                    </h3>
                </div>

                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="paper-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __("Close modal") }}</span>
                </button>
            </div>

            <dl>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("Instructions") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("Please state if you have submitted a paper and if it has been accepted") }}
                </dd>
                <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">{{ __("More help?") }}</dt>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                    {{ __("If you need moore help contact ekonomi@dsv.su.se") }}
                </dd>
            </dl>

            <div class="flex justify-between items-center">
                <button type="button"
                        data-modal-toggle="paper-modal"
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                               rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ __("Close") }}
                </button>
            </div>
        </div>
    </div>
</div>


