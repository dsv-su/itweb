<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TravelRequest extends Model
{
    use HasFactory, HasUuids, Search;

    protected $fillable = [
        'created',
        'state',
        'name',
        'purpose',
        'project',
        'country',
        'comments',
        'paper',
        'contribution',
        'other',
        'departure',
        'return',
        'days',
        'flight',
        'hotel',
        'daily',
        'conference',
        'other_costs',
        'total',
        'manager_comment_id',
        'fo_comment_id',
        'head_comment_id',
    ];

    protected $searchable = [
        'name',
        'purpose',
        'project',
    ];

    protected $casts = [
        'review_details' => 'array',
        'reminder' => 'boolean',
        'last_reminder_sent_at' => 'datetime',
    ];

    public function managerComments(): HasMany
    {
        return $this->hasMany(ManagerComment::class, 'reqid');
    }

    public function headComments(): HasMany
    {
        return $this->hasMany(HeadComment::class, 'reqid');
    }

    public function foComments(): HasMany
    {
        return $this->hasMany(FoComment::class, 'reqid');
    }

    /**
     * Get the manager comment associated with the travelrequest.
     */
    public function managercomment(): BelongsTo
    {
        return $this->belongsTo(ManagerComment::class, 'manager_comment_id');
    }

    /**
     * Get the fo comment associated with the travelrequest.
     */
    public function focomment(): BelongsTo
    {
        return $this->belongsTo(FoComment::class, 'fo_comment_id');
    }

    /**
     * Get the head comment associated with the travelrequest.
     */
    public function headcomment(): BelongsTo
    {
        return $this->belongsTo(HeadComment::class, 'head_comment_id');
    }

    /**
     * Get the dashboard item associated with the travelrequest.
     */
    public function dashboard(): HasOne
    {
        return $this->hasOne(Dashboard::class, 'request_id')->where('type', 'travelrequest');
    }
}
