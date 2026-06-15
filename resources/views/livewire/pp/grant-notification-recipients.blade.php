<div class="mt-5 border rounded-xl shadow-sm p-6 dark:bg-slate-800 dark:border-gray-700">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Grant notification recipients</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Selected SUKAT users receive an email when a proposal is registered as granted.</p>
            <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">Adding or removing a user is not saved until you click Update.</p>
        </div>
        <button
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            class="inline-flex shrink-0 items-center justify-center px-4 py-2 border rounded-md font-semibold text-xs text-white
                   uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition ease-in-out duration-150
                   {{ $this->hasUnsavedChanges()
                        ? 'bg-amber-600 border-amber-600 hover:bg-amber-700 active:bg-amber-800 focus:border-amber-800 ring-amber-300'
                        : 'bg-blue-600 border-transparent hover:bg-indigo-700 active:bg-indigo-800 focus:border-indigo-800 ring-indigo-300' }}">
            <span wire:loading.remove wire:target="save">
                {{ $this->hasUnsavedChanges() ? 'Update required' : 'Update' }}
            </span>
            <span wire:loading wire:target="save">Updating...</span>
        </button>
    </div>

    @if($this->hasUnsavedChanges())
        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-300">
            You have unsaved notification changes. Click <span class="font-semibold">Update required</span> to store them in the database.
        </div>
    @endif

    @if (session()->has('grant_notification_recipients_saved'))
        <div class="mt-3 text-sm text-green-700 dark:text-green-400">
            {{ session('grant_notification_recipients_saved') }}
        </div>
    @endif

    @error('recipients.*.email')
        <div class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
    @enderror

    @if(count($recipients) > 0)
        <div class="mt-4">
            @foreach($recipients as $key => $recipient)
                @php $isStored = $this->recipientIsStored($recipient); @endphp
                <div wire:key="grant-recipient-row-{{ $key }}" class="flex flex-col md:flex-row gap-4 mb-4 items-center transition {{ $isStored ? '' : 'opacity-70' }}">
                    <div
                        class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                               w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{ $recipient['name'] ?? 'Name is missing' }}

                        @php $role = $recipient['role'] ?? 'Other'; @endphp
                        @if($role === 'DSV')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                                {{ $role }}
                            </span>
                        @elseif($role === 'SU')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium bg-purple-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                {{ $role }}
                            </span>
                        @elseif($role === 'Student')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium bg-green-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                {{ $role }}
                            </span>
                        @else
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium bg-gray-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                External
                            </span>
                        @endif

                        @unless($isStored)
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                Pending update
                            </span>
                        @endunless
                    </div>

                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{ $recipient['email'] ?? 'Email is missing' }}
                    </div>

                    <div class="flex items-center">
                        <button type="button" wire:click="removeRecipient({{ $key }})">
                            <svg class="shrink-0 size-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex justify-start">
        <div class="relative w-full"
             x-data
             x-on:keydown.arrow-down.prevent="$wire.moveHighlight(1)"
             x-on:keydown.arrow-up.prevent="$wire.moveHighlight(-1)"
             x-on:keydown.enter.prevent="$wire.addHighlighted()"
             x-on:click.outside="$wire.set('searchRecipient','')"
             x-on:keydown.escape.prevent="$wire.set('searchRecipient','')">
            <input
                id="grant-recipient-input"
                type="text"
                class="p-3 w-full border rounded-md text-sm
                       bg-gray-50 text-gray-800 placeholder:text-gray-500
                       border-slate-300
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400
                       dark:border-slate-700 dark:focus:ring-blue-400"
                wire:model.live.debounce.300ms="searchRecipient"
                placeholder="{{ __('Add a SUKAT recipient by name or email') }}"
                autocomplete="off"
                role="combobox"
                aria-expanded="{{ filled($searchRecipient) ? 'true' : 'false' }}"
                aria-controls="grant-recipient-search-results">

            @if(filled($searchRecipient))
                <div id="grant-recipient-search-results" role="listbox"
                     class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 shadow-md rounded-md p-2 z-50
                            max-h-[calc(100vh-8rem)] overflow-y-auto
                            dark:bg-slate-900 dark:border-slate-800">
                    <ul class="space-y-2">
                        @foreach($sukatUsers as $i => $sukatUser)
                            <li wire:key="grant-recipient-{{ $sukatUser->uid ?? $sukatUser->name }}">
                                <button
                                    type="button"
                                    role="option"
                                    class="w-full text-left border rounded-lg p-2 sm:p-2 transition
                                           border-slate-200 hover:bg-blue-50 active:bg-blue-100
                                           dark:border-slate-800 dark:hover:bg-slate-800 dark:active:bg-slate-700
                                           {{ $highlighted === $i ? 'bg-blue-100 dark:bg-slate-700' : '' }}"
                                    wire:click="addRecipient(@js($sukatUser->uid), @js($sukatUser->name), @js($sukatUser->email), @js($sukatUser->role))"
                                    x-on:click="$wire.set('searchRecipient','')">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-800 dark:text-white shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                        </svg>
                                        <div class="min-w-0 text-slate-900 dark:text-slate-100">
                                            <div class="flex flex-wrap items-center gap-2 leading-tight">
                                                @if($sukatUser->role === 'External')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium bg-gray-50 text-slate-900 dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ __('SU: ') }}
                                                    </span>
                                                @endif

                                                <span class="font-medium">{{ $sukatUser->name }}</span>

                                                @php $role = $sukatUser->role ?? 'Other'; @endphp
                                                @if($role === 'DSV')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'SU')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium bg-purple-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'Student')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium bg-green-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium bg-gray-200 text-gray-600 dark:bg-blue-800/30 dark:text-blue-500">
                                                        External
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 truncate">
                                                {{ $sukatUser->email }}
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
