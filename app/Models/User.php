<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasUuids, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'unit',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'json',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Get the dashboard item associated with the user.
     */
    public function dashboard(): HasOne
    {
        return $this->hasOne(Dashboard::class);
    }

    public function isVice(): bool
    {
        return in_array('vice_head', $this->getRoles());
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->super;
    }

    private function getRoles(): array
    {
        return DB::table('role_user')
            ->where('user_id', $this->id)
            ->pluck('role_id')
            ->toArray();
    }

}
