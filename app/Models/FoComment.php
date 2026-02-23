<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'reqid',
        'user_id',
        'comment'
    ];
    /**
     * Get the travelrequest that belongs to the comment.
     */
    public function travelrequest(): BelongsTo
    {
        return $this->belongsTo(TravelRequest::class);
    }
}
