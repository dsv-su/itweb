<?php

use Livewire\Component;
use App\Services\Directory\SearchPresenters;
use App\Services\Ldap\SukatUser;

new class extends Component
{
    public ?ProjectProposal $proposal = null;
    public array $investigators = [];
    #[Session]
    public array $presenters = [];
    public string $searchPresenter = '';
    /** @var array<int, array{uid:int|string, name:string, role:string, local:bool}> */
    public array $sukatUsers = [];
    public int $highlighted = 0;
    public array $coinvestigators = [];
    public string $external_coinvestigators_name = '';
    public string $external_coinvestigators_email = '';
    public bool $showExternal = false;
    public ?array $person = null;

    public function boot(SearchPresenters $search)
    {
        $this->searchService = $search;
    }

    public function mount($proposal = null): void
    {
        $this->defaultUser();
        if($proposal){
            $this->proposal = $proposal;
            $this->getCoInvestigators();
        }


        $this->getPresenters();
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

    public function moveHighlight(int $direction): void
    {
        $count = count($this->sukatUsers);
        if ($count === 0) return;

        $this->highlighted = ($this->highlighted + $direction + $count) % $count;
    }

    public function addHighlighted(): void
    {
        if (isset($this->sukatUsers[$this->highlighted])) {
            $user = $this->sukatUsers[$this->highlighted];
            $this->addPresenter($user->uid, $user->name, $user->email);
        }
    }

    public function updatedSearchPresenter(): void
    {
        $this->sukatUsers = $this->searchService->execute($this->searchPresenter);
    }

    public function getCoInvestigators(): void
    {
        $names  = $this->proposal->pp['co_investigator_name']  ?? [];
        $emails = $this->proposal->pp['co_investigator_email'] ?? [];
        $types  = $this->proposal->pp['co_investigator_type']  ?? [];
        $roles  = $this->proposal->pp['co_investigator_role']  ?? [];

        foreach ($names as $key => $name) {
            $this->investigators[] = [
                'name'  => $name,
                'email' => $emails[$key] ?? null,
                'type'  => $types[$key]  ?? null,
                'role'  => $roles[$key]  ?? null,
            ];
        }
    }

    public function getPresenters(): void
    {
        $presenters = [];
        $rolesByUid = [];
        $uids = collect($presenters)->pluck('username')->filter()->unique()->values();

        if (empty($presenters)) {
            return;
        }

        if ($uids->isNotEmpty()) {
            // (|(uid=u1)(uid=u2)...)
            $clauses = $uids
                ->map(fn($u) => '(uid=' . $this->searchService->esc($u) . ')')
                ->implode('');
            $filter = '(|' . $clauses . ')';

            $ldapUsers = SukatUser::rawFilter($filter)->get();

            foreach ($ldapUsers as $u) {
                $uid  = is_array($u->uid ?? null) ? ($u->uid[0] ?? null) : ($u->uid ?? null);
                if (!$uid) continue;

                $ents = $u->edupersonentitlement ?? [];
                if (!is_array($ents)) $ents = [$ents];

                $role = 'SU';
                if (in_array(SearchPresenters::ENT_STAFF, $ents, true)) {
                    $role = 'DSV';
                } elseif (in_array(SearchPresenters::ENT_STUDENT, $ents, true)) {
                    $role = 'Student';
                }
                $rolesByUid[$uid] = $role;
            }
        }

        $this->presenters = [];
        foreach ($presenters as $p) {
            $this->presenters[] = [
                'uid'  => $p->username,
                'name' => $p->name,
                'email' => $p->email,
                'type' => $p->description,
                'role' => $rolesByUid[$p->username] ?? null,
            ];
        }
    }

    public function addPresenter($uid, $name, $email): void
    {
        dd($uid, $name, $email);
        $uid = $uid ?: null;
        $name = trim((string) $name);
        $email = trim((string) $email);

        $exists = collect($this->presenters)->contains(function ($item) use ($uid, $name, $email) {
            $itemUid = $item['uid'] ?? null;

            // SUKAT: unique by uid
            if ($uid && $itemUid) {
                return (string)$itemUid === (string)$uid;
            }

            // External: unique by email (fallback to name if no email)
            if (!$uid && !$itemUid) {
                if ($email !== '' && ($item['email'] ?? '') !== '') {
                    return strcasecmp($item['email'], $email) === 0;
                }
                return ($item['name'] ?? '') === $name;
            }

            return false;
        });

        if ($exists) return;

        $role = null;
        if ($uid) {
            $su   = SukatUser::where('uid', $uid)->first(['edupersonentitlement']);
            $ents = $su?->edupersonentitlement ?? [];
            $role = in_array(SearchPresenters::ENT_STAFF, $ents, true) ? 'DSV'
                : (in_array(SearchPresenters::ENT_STUDENT, $ents, true) ? 'Student' : 'SU');
        }

        $this->presenters[] = [
            'uid'   => $uid,
            'name'  => $name,
            'email' => $email,
            'type'  => $uid ? 'sukat' : 'external',
            'role'  => $role,
        ];

        $this->searchPresenter = '';
    }




};
?>

<div class="w-full sm:col-span-2">
    <!--First row -->
    <div class="w-full sm:col-span-2">
        <!-- Rendering SUKAT Co investigators -->
        @if(count($presenters) > 0 )
            @foreach($presenters as $key => $presenter)
                <div wire:key="presenter-row-{{ $key }}"
                     class="flex flex-col md:flex-row gap-4 mb-4 items-center">
                    <!-- Name -->
                    <div
                        class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                                w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$presenter['name'] ?? 'Name is missing'}}
                        @if($presenter['role'] === 'DSV')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-suprimary text-white dark:bg-blue-800/30 dark:text-blue-500">
                            {{$presenter['role']}}</span>
                        @elseif($presenter['role'] === 'SU')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-purple-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                SU</span>
                        @elseif($presenter['role'] === 'Student')
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-green-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                {{$presenter['role']}} </span>
                        @else
                            <span class="inline-flex w-auto items-center gap-x-1.5 py-1 px-1.5 rounded text-xs font-medium
                                 bg-gray-600 text-white dark:bg-blue-800/30 dark:text-blue-500">
                                External</span>
                        @endif
                    </div>
                    <!-- Email -->
                    <div class="font-mono bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600
                    w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        {{$presenter['email'] ?? 'Email is missing'}}
                    </div>
                    <!-- Icon -->
                    <div class="flex items-center">
                        <button type="button" wire:click="remove_presenter({{ $key }})">
                            <svg class="shrink-0 size-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Autocomplete SUKAT -->
    <div class="flex justify-start">
        <div class="relative w-full"
             x-data
             x-on:keydown.arrow-down.prevent="$wire.moveHighlight(1)"
             x-on:keydown.arrow-up.prevent="$wire.moveHighlight(-1)"
             x-on:keydown.enter.prevent="$wire.addHighlighted()"
             x-on:click.outside="$wire.set('searchPresenter','')"
             x-on:keydown.escape.prevent="$wire.set('searchPresenter','')"
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
                placeholder="{{__('Search by name or email')}}"
                autocomplete="off"
                role="combobox"
                aria-expanded="{{ filled($searchPresenter) ? 'true' : 'false' }}"
                aria-controls="search-results"
            />

            @if(filled($searchPresenter))
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
                                    wire:click="addPresenter(@js($sukatUser->uid), @js($sukatUser->name), @js($sukatUser->email))"

                                    x-on:click="$wire.set('searchPresenter','')"
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
            <div class="">
                <p class="text-left font-normal text-gray-900 dark:text-gray-200">{{$person['displayname'][0] ?? ''}}</p>
                <p class="mb-2 text-sm text-left font-medium text-blue-700 dark:text-gray-200">{{$person['title'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">{{$person['ou'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Room: {{$person['roomnumber'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Phone: {{$person['telephonenumber'][0] ?? ''}}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Email: {{$person['mail'][0] ?? ''}}</p>
            </div>
        </div>
@endif

