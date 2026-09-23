<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsVice extends Model
{
    use HasFactory;

    protected $casts = [
        'monthly_stats_recipients' => 'array',
        'grant_notification_recipients' => 'array',
        'sent_notification_recipients' => 'array',
    ];
}
