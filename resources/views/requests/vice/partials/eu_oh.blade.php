<label for="oh_eu" class="mt-2 font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("EU OH Max %") }}<span class="text-red-600"> *</span>
</label>
<input type="number"
       name="oh_eu"
       class="font-mono bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg
                                                focus:ring-primary-600 focus:border-primary-600 w-full p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200
                                                dark:focus:ring-primary-500 dark:focus:border-primary-500"

       placeholder="Max OH"
       value="{{$oh->oh_eu}}">
@error('oh_max')
<div class="text-red-600">{{ $message }}</div>
@enderror

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the error message is present
        if (document.querySelector('.text-red-600')) {
            // Focus on the input field
            document.querySelector('input[name="oh_eu"]').focus();
        }
    });
</script>
