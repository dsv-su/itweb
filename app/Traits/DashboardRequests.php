<?php

namespace App\Traits;

use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Builder;

trait DashboardRequests
{
    public function Dashboardtask($user)
    {
        //Travelrequest
        $manager = collect(Dashboard::where('state', 'submitted')->where('manager_id', $user)->where('type', 'travelrequest')->get());
        $head = collect(Dashboard::where('state', 'manager_approved')->where('head_id', $user)->where('type', 'travelrequest')->get());
        $fo = collect(Dashboard::where('state', 'head_approved')->where('fo_id', $user)->where('type', 'travelrequest')->get());

        //PP
        $head_pp = collect(Dashboard::where('state', 'complete')
                    //->where('head_id', $user)
                    ->whereJsonContains('unit_head_approved', [$user => 0])
                    ->where('type', 'projectproposal')
                    ->whereHas('proposal', function ($projectQuery) {
                        $projectQuery->whereJsonLength('files', '>=', 2);
                    }) // Ensure 'files' contains at least 2 files
                                    ->get());
        $fo_pp = collect(Dashboard::where('state', 'head_approved')
                    ->where('fo_id', $user)
                    ->where('type', 'projectproposal')
                    ->get());
        $vice_pp = collect(Dashboard::where('state', 'fo_approved')
                    ->where('vice_id', $user)
                    ->where('type', 'projectproposal')
                    ->get());

        return $manager->merge($fo)->merge($fo_pp)->merge($head)->merge($head_pp)->merge($vice_pp);
    }

    public function returnedDashboardtask($user)
        {
            return Dashboard::where('user_id', $user)
                ->where(function(Builder $query) {
                    $query->where('state', 'manager_returned')
                        ->orWhere('state', 'fo_returned')
                        ->orWhere('state', 'head_returned')
                        ->orWhere('state', 'vice_returned');
                })
                ->get();
        }
}
