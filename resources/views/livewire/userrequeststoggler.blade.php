<div>
    <!-- Users request -->
    <div class="block py-2 px-4 text-base font-medium text-center text-white bg-blue-600 dark:bg-gray-700 dark:text-gray-400">
        @if(!count($user_requests) > 0)
            {{__("No submitted requests")}}
        @else
            {{__("Your submitted requests")}}
        @endif
    </div>
</div>
