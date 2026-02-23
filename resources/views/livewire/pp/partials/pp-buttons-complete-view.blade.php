@if($proposal->allowComplete() && $proposal->dashboard->status != 'resumed')
    <a
        href="{{ route('pp.complete', $proposal->id) }}#proposal-attachments"
        class="sm:mr-6 inline-flex items-center justify-center
           w-full sm:w-auto
           px-2 py-1 sm:px-1.5 sm:py-1.5
           rounded-md font-semibold text-[0.65rem] sm:text-[0.5rem]
           uppercase tracking-widest
           border border-yellow-500 text-yellow-700 bg-yellow-50
           hover:bg-yellow-500 hover:text-black
           active:bg-yellow-600
           focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 focus:ring-offset-white
           disabled:opacity-25 transition ease-in-out duration-150
           whitespace-nowrap
           dark:border-yellow-400 dark:text-yellow-300 dark:bg-yellow-950/40
           dark:hover:bg-yellow-400 dark:hover:text-black
           dark:active:bg-yellow-500
           dark:focus:ring-yellow-500 dark:focus:ring-offset-2 dark:focus:ring-offset-slate-900">
        Complete
    </a>
@endif
