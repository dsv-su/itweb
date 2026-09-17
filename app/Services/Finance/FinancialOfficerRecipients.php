<?php

namespace App\Services\Finance;

use App\Models\Dashboard;
use App\Models\SettingsFo;
use App\Models\SettingsFoEu;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FinancialOfficerRecipients
{
    public static function forDashboard(Dashboard $dashboard): Collection
    {
        $eu = $dashboard->type === 'projectproposal'
            ? data_get($dashboard->proposal?->pp, 'eu')
            : false;
        $isEu = is_string($eu)
            ? in_array(strtolower(trim($eu)), ['yes', 'y', '1', 'true', 'on'], true)
            : filter_var($eu, FILTER_VALIDATE_BOOL);
        $model = $isEu ? SettingsFoEu::class : SettingsFo::class;
        $ids = $model::where('active', true)->pluck('user_id');

        // Preserve delivery for older requests when no recipients are configured.
        if ($ids->isEmpty() && $dashboard->fo_id) {
            $ids->push($dashboard->fo_id);
        }

        return User::whereIn('id', $ids)->get();
    }
}
