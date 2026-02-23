<a
    {{--}}href="{{ config('app.url') }}"{{--}}
    href="{{ route('pp.show', 'my') }}"
    class="ml-5 flex items-center dark:text-white
         focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2
         dark:focus-visible:ring-blue-300 dark:focus-visible:ring-offset-gray-800 rounded"
    aria-label="DSV ProjectProposals — go to My Proposals"
>
    <div class="flex items-center opacity-90 dark:text-white">
    <span
        class="px-1 py-1 text-xl leading-none border-2 border-suprimary rounded-lg dark:border-gray-200"
        aria-hidden="true"
    >
      DSV
    </span>

        <span class="ml-0.5 mb-1 text-xl font-sudepartment whitespace-nowrap">
      {{ __("ProjectProposals") }}
    </span>

        <span class="sr-only">Home</span>
    </div>
</a>
