@error($field)
    <p id="{{ $field }}-error" class="mt-3 text-sm leading-6 text-red-700 dark:text-red-400">
        {{ $message }}
    </p>
@enderror
