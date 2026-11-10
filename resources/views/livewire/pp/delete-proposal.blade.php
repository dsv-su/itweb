<button
    type="button"
    wire:click="delete"
    wire:confirm="Are you sure you want to permanently delete this proposal and its uploaded files? This cannot be undone. A confirmation email will be sent to the principal investigator."
    wire:loading.attr="disabled"
    class="inline-flex min-h-8 items-center justify-center rounded-lg border border-red-700 bg-white px-2.5 py-1 text-xs font-semibold text-red-800 shadow-sm hover:bg-red-700 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red-700 focus-visible:ring-offset-2 disabled:opacity-50 dark:border-red-400 dark:bg-transparent dark:text-red-300 dark:hover:bg-red-700 dark:hover:text-white"
>
    Delete
</button>
