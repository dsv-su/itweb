<?php

namespace App\Http\Middleware;

use App\Models\Dashboard;
use Closure;
use Illuminate\Http\Request;
use Statamic\Auth\User;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserForReview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure the correct reviewer reviews the request
        $id = basename($request->path());
        $user = User::current();

        //$dashboard = Dashboard::find($id) ?? Dashboard::where('request_id', $id)->first();

        if ($dashboard =  Dashboard::where('request_id', $id)->first()) {
        } else {
            $dashboard = Dashboard::find($id);
        }

        if (!$dashboard) {
            abort(404, 'Dashboard not found');
        }

        // Ensure state is treated as a string
        $state = (string) $dashboard->state;

        $reviewers = [
            'complete' => [
                'travelrequest' => $dashboard->manager_id,
                //'projectproposal' => $dashboard->vice_id,
                'projectproposal' => is_array($dashboard->unit_heads) ? $dashboard->unit_heads : [$dashboard->unit_heads], // Ensure array
            ],
            'manager_approved' => $dashboard->head_id,
            'head_approved' => [
                'travelrequest' => $dashboard->fo_id,
                'projectproposal' => $dashboard->fo_id,
            ],
            //'vice_approved' => is_array($dashboard->unit_heads) ? $dashboard->unit_heads : [$dashboard->unit_heads], // Ensure array
            'fo_approved' => $dashboard->vice_id,
        ];

        $allowedReviewers = $reviewers[$state] ?? null;

        if (is_array($allowedReviewers) && !isset($allowedReviewers['travelrequest'])) {
            // Check if the user is in an array (for cases like 'vice_approved')
            if (in_array($user->id, $allowedReviewers['projectproposal'])) {
                return $next($request);
            }
        } else {
            // Handle cases where state has nested types
            $allowed = is_array($allowedReviewers) ? ($allowedReviewers[$dashboard->type] ?? null) : $allowedReviewers;

            if ($user->id == $allowed) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
        return redirect('/');
    }



}
