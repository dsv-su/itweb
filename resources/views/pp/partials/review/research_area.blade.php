<div class="w-full">
    <div class="font-mono @if($type == 'complete') bg-blue-300 bg-opacity-60 @else bg-gray-50 @endif
        border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
        block w-full p-2.5 @if($type == 'complete') dark:bg-blue-900 @else dark:bg-gray-700 @endif
        dark:border-gray-600
        dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
        {{$proposal['pp']['research_area']}}
    </div>
</div>
