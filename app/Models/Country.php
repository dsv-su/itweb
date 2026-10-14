<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'country',
        'allowance',
        'year'
    ];

    public function getLocalizedNameAttribute(): ?string
    {
        return self::localizedName($this->country);
    }

    public static function localizedName(?string $name): ?string
    {
        if ($name === null || app()->getLocale() !== 'en') {
            return $name;
        }

        $translations = trans('countries', [], 'en');

        return is_array($translations) ? ($translations[$name] ?? $name) : $name;
    }
}
