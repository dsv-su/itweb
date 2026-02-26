@if($tr->state == 'submitted')
    <span class="bg-blue-100 text-blue-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">{{__("Submitted")}}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-blue-600 rounded-full dark:bg-blue-500" style="width: 33%"></div>
    </div>
@elseif ($tr->state == 'manager_approved')
    <span class="bg-blue-100 text-blue-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">{{__("Processed by manager") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-blue-600 rounded-full dark:bg-blue-500" style="width: 33%"></div>
    </div>
@elseif ($tr->state == 'manager_denied')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Denied") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'manager_returned')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Returned") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'head_approved')
    <span class="bg-blue-100 text-blue-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">{{__("Being processed by FO") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-blue-600 rounded-full dark:bg-blue-500" style="width: 90%"></div>
    </div>
@elseif ($tr->state == 'fo_denied')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Denied") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'fo_returned')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Returned") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'fo_approved')
    <span class="bg-green-100 text-green-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">{{__("Approved") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-blue-600 rounded-full dark:bg-blue-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'head_denied')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Denied") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@elseif ($tr->state == 'head_returned')
    <span class="bg-red-100 text-red-800 font-medium mr-2 px-6 py-2 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">{{__("Returned") }}</span>
    <div class="w-full h-4 mb-4 mt-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-4 bg-red-600 rounded-full dark:bg-red-500" style="width: 100%"></div>
    </div>
@endif
