<p class="mt-1 text-gray-600 dark:text-gray-400">Set the DSV registrator email</p>
<div class=" mt-5 border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
    <form action="{{ route('vice_settings.registrator') }}" method="POST">
        @csrf
        <label for="dsv_registrator" class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("DSV Registrator email") }}<span class="text-red-600"> *</span>
        </label>
        <input type="text"
               name="dsv_registrator"
               class="font-mono bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg
                                                focus:ring-primary-600 focus:border-primary-600 w-full p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200
                                                dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="DSV Registrator email"
               value="{{$dsv->registrator}}">
        @error('oh_max')
        <div class="text-red-600">{{ $message }}</div>
        @enderror

        <div class="mt-3">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white
                                                            uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300
                                                            disabled:opacity-25 transition ease-in-out duration-150">
                Update
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the error message is present
        if (document.querySelector('.text-red-600')) {
            // Focus on the input field
            document.querySelector('input[name="dsv_registrator"]').focus();
        }
    });
</script>
