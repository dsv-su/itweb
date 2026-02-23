<div class="inline-block">
    @if(in_array(auth()->user()->id, $finaceOfficers))
        <select
            wire:model.live="fo_user_id"
            class="bg-yellow-50 text-yellow-700 border border-yellow-400 text-[0.65rem] font-medium
                   me-1 px-1 py-1 rounded hover:text-yellow-800
                   dark:bg-yellow-800 dark:text-yellow-300 appearance-none leading-none">
            @foreach ($fos as $fo)
                <option value="{{ $fo->id }}" @selected($fo->id == $proposal->fo_user_id)>{{ $fo->name }}</option>
            @endforeach
        </select>
    @else
        {{--}}<span
            class="bg-yellow-50 text-yellow-700 border border-yellow-400 text-[0.65rem] font-medium
                   me-1.5 px-1 py-0.5 rounded hover:text-yellow-800
                   dark:bg-yellow-800 dark:text-yellow-300 appearance-none leading-none">{{--}}
        <span class="font-medium">
            {{$proposal->foUser->name}}
        </span>
    @endif
</div>




