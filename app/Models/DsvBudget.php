<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsvBudget extends Model
{
    use HasFactory;

    protected $fillable = ['research_area', 'preapproved_total', 'budget_total', 'cost_total'];
    protected $casts = [
        'research_area' => 'array',
    ];
}
