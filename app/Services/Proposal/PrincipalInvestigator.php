<?php

namespace App\Services\Proposal;

use App\Models\ProjectProposal;
use App\Models\User;
use App\Services\Ldap\SukatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrincipalInvestigator
{
    public function directoryProfile(string $uid): array
    {
        $person = SukatUser::where('uid', $uid)->first();
        $name = $person?->getFirstAttribute('displayName') ?: $person?->getFirstAttribute('cn');
        $email = $person?->getFirstAttribute('mail');

        if (! $name || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'principal_investigator_uid' => 'Select a SUKAT investigator with a valid name and email address.',
            ]);
        }

        return ['uid' => $uid, 'name' => $name, 'email' => $email];
    }

    public function resolve(Request $request, ProjectProposal $proposal): User
    {
        $actor = $request->user();
        $canAssign = $actor->canAssignPrincipalInvestigator();
        $dashboard = $proposal->dashboard;
        $reviewers = array_filter([
            $proposal->user_id, $dashboard?->user_id, $dashboard?->vice_id,
            $dashboard?->fo_id, ...(array) ($dashboard?->unit_heads ?? []),
        ]);
        abort_unless($canAssign || in_array($actor->id, $reviewers, true), 403);

        if ($request->filled('principal_investigator_uid')) {
            abort_unless($canAssign, 403);
            $profile = $this->directoryProfile($request->string('principal_investigator_uid')->toString());
            $owner = User::firstOrCreate(['email' => $profile['email']], ['name' => $profile['name']]);
            if ($owner->wasRecentlyCreated) {
                // The same project-leader group assigned on first SU login.
                DB::table('group_user')->insert(['user_id' => $owner->id, 'group_id' => 'projektledare']);
            }
            $request->merge([
                'principal_investigator' => $profile['name'],
                'principal_investigator_email' => $profile['email'],
            ]);
        } else {
            $owner = User::findOrFail($proposal->user_id);
            // Read-only form fields are still client input; retain the stored identity.
            $request->merge([
                'principal_investigator' => $proposal->pp['principal_investigator'] ?? $owner->name,
                'principal_investigator_email' => $proposal->pp['principal_investigator_email'] ?? $owner->email,
            ]);
        }

        return $owner;
    }
}
