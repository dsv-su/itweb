<div wire:poll.visible>
    @if(count($dashboard) > 0)
        <span class="md:hidden relative flex h-3 w-3 -mr-3">
            <span class="flex absolute top-0 end-0 -mt-2 -me-2">
            <span class="animate-ping absolute inline-flex size-full rounded-full bg-blue-400 opacity-75 dark:bg-blue-600"></span>
                <span class="relative inline-flex text-xs bg-blue-500 text-white rounded-full py-0.5 px-1.5">
                  {{count($dashboard)}}
                </span>
            </span>
        </span>
    @endif
</div>
