<div id="{{ $warningId }}" class="mt-3 min-w-0 break-words rounded-lg border border-amber-600 bg-amber-50 p-3 text-sm leading-6 text-amber-950 dark:border-amber-400 dark:bg-gray-800 dark:text-amber-200">
    <p class="font-semibold">{{ __('No project selected') }}</p>
    <p class="mt-1">{{ __('All travel requests should be linked to a project. A request without a project may be returned for clarification.') }}</p>
    <p class="mt-1">{{ __('If you do not know the project or cannot select one, explain the reason in Comments.') }}</p>
    <a href="#comments" x-on:click.prevent="document.getElementById('comments').focus()" class="mt-2 inline-flex min-h-6 items-center font-medium underline underline-offset-2">{{ __('Explain in Comments') }}</a>
</div>
