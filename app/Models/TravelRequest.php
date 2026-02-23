<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use \Illuminate\Database\Eloquent\Concerns\HasUuids;

class TravelRequest extends Model
{
    use HasFactory;
    use Search;
    use HasUuids;

    protected $fillable = [
        'created',
        'state',
        'name',
        'purpose',
        'project',
        'country',
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
        'head_comment_id'
    ];

    protected $searchable = [
        'name',
        'purpose',
        'project',
    ];

    /**
     * Get the manager comment associated with the travelrequest.
     */
    public function managercomment(): HasOne
    {
        return $this->hasOne(ManagerComment::class, 'reqid');
    }

    /**
     * Get the fo comment associated with the travelrequest.
     */
    public function focomment(): HasOne
    {
        return $this->hasOne(FoComment::class, 'reqid');
    }

    /**
     * Get the head comment associated with the travelrequest.
     */
    public function headcomment(): HasOne
    {
        return $this->hasOne(HeadComment::class, 'reqid');
    }

    /**
     * Get the dashboard item associated with the travelrequest.
     */
    public function dashboard(): HasOne
    {
        return $this->hasOne(Dashboard::class);
    }
}
