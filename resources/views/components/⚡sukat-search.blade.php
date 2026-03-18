<?php

use Livewire\Component;
use App\Services\Directory\SearchPresenters;
use App\Services\Ldap\SukatUser;

new class extends Component
{
    #[Session]
    public array $presenters = [];
    public string $searchPresenter = '';
    /** @var array<int, array{uid:int|string, name:string, role:string, local:bool}> */
    public array $sukatUsers = [];
    public int $highlighted = 0;

    public ?array $person = null;

    public function boot(SearchPresenters $search)
    {
        $this->searchService = $search;
    }

    public function mount($proposal = null): void
    {
        $this->defaultUser();

        if ($this->searchPresenter !== '') {
            $this->sukatUsers = $this->searchService->execute($this->searchPresenter);
        }
    }

    public function defaultUser(): void
    {
        $user = null;

        if (App::isLocal()) {
            $user = SukatUser::where('uid', 'gwett')->first();
        } else {
            $email = auth()->user()?->email;
            if ($email) {
                $user = SukatUser::where('mail', $email)->first();
            }
        }

        $this->person = $user?->toArray(); // null-safe
    }
    public function selectUser(int $index = -1): void
    {
        // Prefer passing an index from the UI; default to the highlighted item.
        $selected = $index >= 0
            ? ($this->sukatUsers[$index] ?? null)
            : ($this->sukatUsers[$this->highlighted] ?? null);

        if ($selected) {
            $this->person = is_object($selected) ? (array) $selected : $selected;
        }

        $this->resetData();
    }
    public function resetData(): void
    {
        $this->searchPresenter = '';
        $this->sukatUsers = [];
        $this->highlighted = 0;
    }

    public function moveHighlight(int $direction): void
    {
        $count = count($this->sukatUsers);
        if ($count === 0) return;

        $this->highlighted = ($this->highlighted + $direction + $count) % $count;

    }

    public function addHighlighted(): void
    {
        $this->selectUser($this->highlighted);
    }

    public function updatedSearchPresenter(): void
    {
        $this->sukatUsers = $this->searchService->execute($this->searchPresenter);
    }

};
?>

<div class="w-full sm:col-span-2">
    <div class="relative grow max-w-96 mr-2 md:order-none">
        <div class="relative flex items-center">
            {{-- ... existing code ... --}}
        </div>

        @if(!empty($query))
            <div class="fixed top-0 bottom-0 left-0 right-0 z-10" wire:click="resetData"></div>

            <div class="origin-top-right absolute md:left-0 mt-2 z-20 w-72 md:w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 dark:text-white ring-1 ring-black ring-opacity-5 text-left">
                <div class="py-1 text-sm text-gray-700 dark:text-white text-left">
                    @if(!empty($sukatusers))
                        @foreach($sukatusers as $i => $users)
                            @if($users['edupersonaffiliation'] ?? null)
                                @if(is_array($users['edupersonaffiliation']))
                                    <a
                                        href="#"
                                        wire:click.prevent="selectUser({{ $i }})"
                                        class="block py-2 hover:bg-gray-100 hover:text-gray-900 {{ $highlightIndex === $i ? 'dark:text-black bg-gray-100 dark:bg-gray-300' : 'dark:text-gray-200' }}
                                            dark:hover:bg-gray-300 dark:hover:text-black"
                                    >
                                        <span class="px-4">{{ $users['cn'][0] }}</span>
                                        {{-- ... existing code ... --}}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="flex justify-start">
        <div class="relative w-full"
             x-data
             x-on:keydown.arrow-down.prevent="$wire.moveHighlight(1)"
             x-on:keydown.arrow-up.prevent="$wire.moveHighlight(-1)"
             x-on:keydown.enter.prevent="$wire.selectUser()"
             x-on:click.outside="$wire.resetData()"
             x-on:keydown.escape.prevent="$wire.resetData()"
        >
            <input
                id="presenter-input"
                type="text"
                class="p-3 w-full border rounded-md text-sm
                 bg-gray-50 text-gray-800 placeholder:text-gray-500
                 border-slate-300
                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400
                 dark:border-slate-700 dark:focus:ring-blue-400"
                wire:model.live.debounce.300ms="searchPresenter"
                placeholder="{{__("Search by name or email")}}"
                autocomplete="off"
                role="combobox"
                aria-expanded="{{ (filled($searchPresenter) && count($sukatUsers)) ? 'true' : 'false' }}"
                aria-controls="search-results"
            />

            @if(filled($searchPresenter) && count($sukatUsers))
                <div id="search-results" role="listbox"
                     class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 shadow-md rounded-md p-2 z-50
                          max-h-[calc(100vh-8rem)] overflow-y-auto
                          dark:bg-slate-900 dark:border-slate-800">
                    <ul class="space-y-2">
                        @foreach($sukatUsers as $i => $sukatUser)
                            <li wire:key="presenter-{{ $sukatUser->uid ?? $sukatUser->name }}">
                                <button
                                    type="button"
                                    role="option"
                                    class="w-full text-left border rounded-lg p-2 sm:p-2 transition
                                       border-slate-200 hover:bg-blue-50 active:bg-blue-100
                                       dark:border-slate-800 dark:hover:bg-slate-800 dark:active:bg-slate-700
                                       {{ $highlighted === $i ? 'bg-blue-100 dark:bg-slate-700' : '' }}"
                                    x-on:click.prevent="$wire.selectUser({{ $i }})"
                                >
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-800 dark:text-white shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                        </svg>
                                        <div class="min-w-0 text-slate-900 dark:text-slate-100">
                                            {{-- line 1: name + badges --}}
                                            <div class="flex flex-wrap items-center gap-2 leading-tight">
                                                @if($sukatUser->role === 'External')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-gray-50 text-slate-900 dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ __('SU: ') }}
                                                    </span>
                                                @endif

                                                <span class="font-medium">
                                                    {{ $sukatUser->name }}
                                                </span>

                                                @php $role = $sukatUser->role ?? 'Other'; @endphp
                                                @if($role === 'DSV')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'SU')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-purple-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @elseif($role === 'Student')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-green-500 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                                        {{ $role }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-1.5 rounded-md text-xs font-medium
                                                                bg-gray-200 text-gray-600 dark:bg-blue-800/30 dark:text-blue-500">
                                                        External
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- line 2: email --}}
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

    @if($person)
        <div class="mx-auto mt-4 flex max-w-xs flex-col text-left rounded-xl border px-4 py-4 md:max-w-lg md:flex-row md:items-start md:text-left">
            <div class="w-full max-w-[320px] min-h-[160px] rounded-xl border border-slate-200 bg-white p-4 shadow-sm
            dark:border-slate-800 dark:bg-slate-900">

                <p class="text-left font-normal text-gray-900 dark:text-gray-200">
                    {{ data_get($person, 'displayname.0') ?? data_get($person, 'name') ?? '' }}
                </p>
                <p class="mb-2 text-sm text-left font-medium text-blue-700 dark:text-gray-200">
                    {{ data_get($person, 'title.0') ?? data_get($person, 'title') ?? '' }}
                </p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    {{ data_get($person, 'ou.0') ?? data_get($person, 'ou') ?? '' }}
                </p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Phone:
                    {{ data_get($person, 'telephonenumber.0') ?? data_get($person, 'telephonenumber') ?? '' }}
                </p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Email:
                    {{ data_get($person, 'mail.0') ?? data_get($person, 'email') ?? '' }}
                </p>
            </div>
        </div>
    @endif
</div>
