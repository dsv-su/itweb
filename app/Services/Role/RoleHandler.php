<?php

namespace App\Services\Role;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleHandler
{
    /**
     * @var User The authenticated user instance.
     */
    protected User $user;

    /**
     * RoleHandler constructor.
     *
     * @param User $user The user whose roles are being checked.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user's roles.
     *
     * @return array The list of roles assigned to the user.
     */
    public function show(): array
    {
        $roles = [];

        if ($this->checkVice()) {
            $roles[] = 'Vice';
        }

        if ($this->checkUH()) {
            $roles[] = 'UnitH';
        }

        if ($this->checkFO()) {
            $roles[] = 'FinancialO';
        }

        return $roles;
    }

    /**
     * Check if the user has the "Vice Head" role.
     *
     * @return bool True if the user is a Vice Head, false otherwise.
     */
    public function checkVice(): bool
    {
        return DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->where('user_id', $this->user->id)
            ->exists();
    }

    /**
     * Check if the user is a "Unit Head".
     *
     * @return bool True if the user is a Unit Head, false otherwise.
     */
    public function checkUH(): bool
    {
        return DB::table('group_user')
            ->where('group_id', 'enhetschef')
            ->where('user_id', $this->user->id)
            ->exists();
    }

    /**
     * Check if the user is a "FO".
     *
     * @return bool True if the user is FO, false otherwise.
     */
    public function checkFO(): bool
    {
        return DB::table('settings_fos')
            ->where('user_id', $this->user->id)
            ->exists();
    }
}

