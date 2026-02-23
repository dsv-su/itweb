<div>
    <div class="block py-2 px-4 text-base font-medium text-center text-white bg-blue-600 dark:bg-gray-700 dark:text-white">
        @if(!(count($requests ?? []) > 0 or count($returned ?? []) > 0))
            {{__("No notifications")}}
        @else
            {{__("Notifications")}}
        @endif
    </div>
</div>
