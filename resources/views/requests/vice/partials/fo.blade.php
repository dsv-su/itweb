<div class=" mt-5 border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
    <div class="w-1/2 border border-blue-500 text-sm text-blue-600 rounded-lg p-5 dark:bg-blue-600/[.15]">
        {{\App\Models\SettingsFo::find(1)->name ?? 'Not set'}}
    </div>
    <form action="{{ route('fo') }}" method="POST">
        @csrf
        <div >
            <div class="mt-3">
                <label for="fo_select" class="block text-sm font-medium text-gray-700">Change:</label>
                <select id="fo_select" name="selected_fo" class="mt-1 block w-1/2 py-2 px-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    @foreach($fos as $fo)
                        <option value="{{ $fo->id }}" @if($fo->active) selected @endif>{{ $fo->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mt-3">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                               uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                               disabled:opacity-25 transition ease-in-out duration-150">
                    Update
                </button>
            </div>

        </div>
    </form>
</div>
