<?php

namespace App\Services\Directory;

use App\Services\Ldap\SukatUser;
use Illuminate\Support\Str;
use stdClass;

class SearchPresenters
{
    public const ENT_STAFF   = 'urn:mace:swami.se:gmai:dsv-user:staff';
    public const ENT_STUDENT = 'urn:mace:swami.se:gmai:dsv-user:student';

    public function __construct(
        //LDAP/local lookups, etc.
    ) {}

    /** Escape for LDAP filter */
    public function esc(string $value): string
    {
        if (function_exists('ldap_escape')) {
            return ldap_escape($value, '', LDAP_ESCAPE_FILTER);
        }
        return strtr($value, ['\\'=>'\5c','*'=>'\2a','('=>'\28',')'=>'\29',"\0"=>'\00']);
    }

    public function execute(string $query, int $limit = 10, bool $external = true): array
    {
        $q = trim($query);

        // Clear on empty input
        if ($q === '') {
            return [];
        }

        // Split on whitespace, normalize
        $searchTerms = collect(preg_split('/\s+/', $q))
            ->filter() // remove empties
            ->map(fn ($t) => trim($t))
            ->unique()
            ->values();

        if ($searchTerms->isEmpty()) {
            return [];
        }

        $filterParts = $searchTerms->map(function ($term) {
            $safe = $this->esc($term);
            return "(|(givenName={$safe}*)(sn={$safe}*)(mail={$safe}*))";
        });

        $ldapFilter = '(&' . $filterParts->implode('') . ')';

        // Single LDAP fetch
        $sukatUsers = SukatUser::rawFilter($ldapFilter)->get();

        // Map to role from entitlements
        $users = collect($sukatUsers)
            ->filter(fn ($su) => !empty($su->uid[0] ?? null)) // must have uid
            ->map(function ($su) {
                $entitlements = collect($su->edupersonentitlement ?? []);
                $role = 'SU';
                if ($entitlements->contains(fn ($e) => Str::contains($e, self::ENT_STAFF))) {
                    $role = 'DSV';
                } elseif ($entitlements->contains(fn ($e) => Str::contains($e, self::ENT_STUDENT))) {
                    $role = 'Student';
                }

                $user           = new stdClass();
                $user->uid      = $su->uid[0] ?? null;
                $user->name     = $su->displayName[0] ?? ($su->cn[0] ?? $user->uid);
                $user->email    = $su->mail[0] ?? $user->uid;
                $user->role     = $role;
                $user->local    = false;

                return $user;
            })
            ->unique('uid') // avoid dupes if directory returns overlapping entries
            ->values();

        // Prioritize: DSV staff first, then External/local, then Student, then Other
        $priority = [
            'DSV'      => 0,  // staff entitlement
            'SU'       => 1,  // su external
            'Student'  => 2,
            'Other'    => 3, // external
        ];

        $users = $users
            ->sortBy(function ($u) use ($priority) {
                return $priority[$u->role] ?? 99;
            })
            ->values();


        return $users->take($limit)->values()->all();
    }
}
