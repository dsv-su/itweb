<?php

namespace App\Models;

use App\Workflows\States\DashboardState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

class Dashboard extends Model
{
    use HasFactory, HasStates;

    protected $fillable = [
        'request_id',
        'name',
        'state',
        'created',
        'status',
        'type',
        'user_id',
        'manager_id',
        'fo_id',
        'head_id',
        'vice_id',
        'multiple_heads',
        'unit_heads',
        'unit_head_approve'
    ];

    protected $casts = [
        'state' => DashboardState::class,
        'unit_heads' => 'array',
        'unit_head_approve' => 'array',
    ];

    /**
     * Get the user that belongs to the dashboard.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the travelrequest that belongs to the dashboard.
     */
    public function travel(): BelongsTo
    {
        return $this->belongsTo(TravelRequest::class, 'request_id');
    }

    /**
     * Get the projectproposal that belongs to the dashboard.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProjectProposal::class, 'request_id');
    }
}
