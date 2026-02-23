<?php

namespace App\Livewire\Pp;

use App\Models\ProjectProposal;
use App\Services\Directory\SearchPresenters;
use App\Services\Ldap\SukatUser;
use Livewire\Attributes\Session;
use Livewire\Component;

class CoInvestigators extends Component
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

    public function boot(SearchPresenters $search)
    {
        $this->searchService = $search;
    }
    public function mount($proposal = null): void
    {
        if($proposal){
            $this->proposal = $proposal;
            $this->getCoInvestigators();
        }

        $this->getPresenters();
        if ($this->searchPresenter !== '') {
            $this->sukatUsers = $this->searchService->execute($this->searchPresenter);
        }
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
    public function remove_presenter($index)
    {
        array_splice($this->presenters, $index, 1);
    }

    public function addExternal()
    {
        $this->showExternal = true;
    }

    public function saveExternal(): void
    {

        /*$this->validate([
            'external_coinvestigators_name' => 'required|string|max:255',
            'external_coinvestigators_email' => 'required|email|max:255',
        ]);*/

        $name  = trim($this->external_coinvestigators_name);
        $email = trim($this->external_coinvestigators_email);

        if ($name === '' || $email === '') {
            return; // or show validation error
        }

        // Add to the same list as SUKAT entries
        $this->addPresenter(null, $name, $email);

        $this->reset('external_coinvestigators_name', 'external_coinvestigators_email');
        $this->showExternal = false;

    }

    public function render()
    {
        return view('livewire.pp.co-investigators');
        /*return view('livewire.pp.co-investigators', [
        'searchPresenter' => $this->searchPresenter,
        ]);*/
    }
}
