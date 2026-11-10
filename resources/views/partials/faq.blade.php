<div x-data="{ open: false }" x-id="['faq-question', 'faq-answer']"
     class="my-4 overflow-hidden rounded-xl border border-gray-500 bg-white text-gray-900 dark:border-gray-400 dark:bg-gray-900 dark:text-gray-100">
    <h2 class="not-prose m-0">
        <button type="button" @click="open = !open"
                :id="$id('faq-question')" :aria-expanded="open.toString()" :aria-controls="$id('faq-answer')"
                class="flex w-full items-center justify-between gap-4 p-4 text-left text-base font-semibold hover:underline">
            <span>{{ $content->question }}</span>
            <svg class="h-4 w-4 shrink-0" :class="{ 'rotate-180': open }" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6" />
            </svg>
        </button>
    </h2>
    <div x-show="open" x-cloak :id="$id('faq-answer')" :aria-labelledby="$id('faq-question')" role="region" class="px-4 pb-4">
        {{ $content->answer }}
    </div>
</div>
