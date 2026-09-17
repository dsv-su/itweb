@php
    $configured = $settingsModel::where('active', true)->orderBy('id')->get();
    $selected = old($field, $configured->pluck('user_id')->all());
@endphp
<div class="mt-5 border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
    <p class="text-sm text-gray-700 dark:text-gray-300">Current recipients: {{ $configured->pluck('name')->join(', ') ?: 'Not set' }}</p>
    <form action="{{ route($routeName) }}" method="POST">
        @csrf
        <fieldset class="mt-3 space-y-2">
            <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Select one or more financial officers to receive notifications.</legend>
            @forelse($fos as $fo)
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="{{ $field }}[]" value="{{ $fo->id }}"
                           @checked(in_array($fo->id, (array) $selected)) class="rounded border-gray-300">
                    <span>{{ $fo->name }}</span>
                </label>
            @empty
                <p class="text-sm text-gray-600 dark:text-gray-400">No financial officers are available.</p>
            @endforelse
        </fieldset>
        @foreach($errors->get($field) + $errors->get($field.'.*') as $messages)
            @foreach($messages as $message)
                <p class="mt-2 text-sm text-red-600" role="alert">{{ $message }}</p>
            @endforeach
        @endforeach
        <button type="submit" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-semibold text-sm text-white" @disabled($fos->isEmpty())>Update</button>
    </form>
</div>
