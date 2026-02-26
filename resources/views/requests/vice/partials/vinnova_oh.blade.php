<label for="oh_vinnova" class="mt-2 font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Vinnova OH Max %") }}<span class="text-red-600"> *</span>
</label>
<input type="number"
       name="oh_vinnova"
       class="font-mono bg-gray-100 border border-gray-300 text-gray-400 text-sm rounded-lg
       w-full p-2.5 opacity-50 pointer-events-none
       dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500"

       placeholder="Max OH"
       value="{{$oh->oh_vinnova}}" disabled>
@error('oh_max')
<div class="text-red-600">{{ $message }}</div>
@enderror

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the error message is present
        if (document.querySelector('.text-red-600')) {
            // Focus on the input field
            document.querySelector('input[name="oh_vinnova"]').focus();
        }
    });
</script>
