<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\ModelStates\HasStates;

class ProjectProposal extends Model
{
    use HasFactory, HasStates, HasUuids;

    protected $fillable = ['user_id', 'name', 'created', 'status_stage1' ,'status_stage2', 'status_stage3', 'pp', 'files'];
    protected $casts = [
        'pp' => 'array',
        'files' => 'array',
    ];

    /**
     * Get the dashboard item associated with the proposal.
     */
    public function dashboard(): HasOne
    {
        return $this->hasOne(Dashboard::class, 'request_id');
    }

    public function foUser(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,        // final model
            Dashboard::class,  // intermediate model
            'request_id',      // dashboards.request_id -> project_proposals.id
            'id',           // users.id -> dashboards.fo_id
            'id',            // project_proposals.id
            'fo_id'      // dashboards.fo_id
        );
    }

    public function allowEdit()
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        //$allowed_roles_I = [$dashboard?->user_id, $dashboard?->vice_id, $dashboard?->fo_id];
        //$allowed_roles_II = is_array($dashboard?->unit_heads) ? $dashboard->unit_heads : [$dashboard->unit_heads];
        //$allowed_roles = array_filter(array_merge($allowed_roles_I, $allowed_roles_II)); // Remove null values

        $allowed_roles = [$dashboard?->user_id];

        // && $dashboard->state == 'fo_approved' //alternative for only approved proposals
        return (in_array($user->id, $allowed_roles) && in_array($dashboard->state, ['submitted', 'complete']));
    }

    public function allowContinue()
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();
        $allowed_roles = [$dashboard?->user_id];

        return (in_array($user->id, $allowed_roles) && in_array($dashboard->state, ['pending','saved']));
    }

    public function allowComplete(): bool
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        if (!$dashboard || (string) $dashboard->state !== 'submitted') {
            return false;
        }

        return $user->id === $dashboard->user_id;
    }

    public function allowResume(): bool
    {
        $user      = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        // no Dashboard → can’t resume
        if (! $dashboard) {
            return false;
        }

        // state must be one of these three
        $allowed = ['head_returned', 'fo_returned', 'final_returned'];
        if (! in_array((string) $dashboard->state, $allowed, true)) {
            return false;
        }

        // only the owner may resume
        return $user->id === $dashboard->user_id;
    }


    public function allowUpload():bool
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        if (!$dashboard || (string)$dashboard->state !== 'vice_approved' || count($this->files ?? []) > 1) {
            return false;
        }

        return $user->id === $dashboard->user_id;
    }

    public function allowSend(): bool
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        if (!$dashboard || (string) $dashboard->state !== 'final_approved') {
            return false;
        }

        return $user->id === $dashboard->user_id;
    }

    public function allowGrant(): bool
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        if (!$dashboard || (string) $dashboard->state !== 'sent') {
            return false;
        }

        return $user->id === $dashboard->user_id;
    }

    public function allowReject(): bool
    {
        $user = Auth::user();
        $dashboard = Dashboard::where('request_id', $this->id)->first();

        if (!$dashboard || (string) $dashboard->state !== 'sent') {
            return false;
        }

        return $user->id === $dashboard->user_id;
    }

    public function hasAtLeastFiles(int $min = 2): bool
    {
        return count($this->files ?? []) >= $min;
    }

    public function hasAtLeastFilesOfType(string $type, int $min = 2): bool
    {
        $count = 0;

        foreach ($this->files as $meta) {
            if (Arr::get($meta, 'type') === $type) {
                $count++;
                if ($count >= $min) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get only the files of a given type.
     */
    public function filesByType(string $type): array
    {
        return array_filter(
            $this->files,
            fn(array $meta) => Arr::get($meta, 'type') === $type
        );
    }

    /**
     * Return an array of review statuses for that type.
     *
     * e.g. ['pending', 'approved', 'pending']
     */
    public function reviewStatusesByType(string $type): array
    {
        return array_map(
            fn(array $meta) => Arr::get($meta, 'review'),
            $this->filesByType($type)
        );
    }
    /**
     * Check if *all* files of this type are approved.
     */
    public function isTypeFullyApproved(string $type): bool
    {
        foreach ($this->filesByType($type) as $meta) {
            if (Arr::get($meta, 'review') !== 'approved') {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if *any* file of this type is still pending.
     */
    public function hasPendingOfType(string $type): bool
    {
        foreach ($this->filesByType($type) as $meta) {
            if (Arr::get($meta, 'review') === 'pending') {
                return true;
            }
        }
        return false;
    }
/*
$pp = ProjectProposal::findOrFail($id);

// get raw statuses:
$statuses = $pp->reviewStatusesByType('budget');
// e.g. ['pending','approved','pending']

// boolean checks:
if ($pp->isTypeFullyApproved('budget')) {
    // all budget files are approved
}

if ($pp->hasPendingOfType('budget')) {
    // there are still some pending budget files
}
*/

}
