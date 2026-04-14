<?php

namespace App\Http\Middleware;

use App\Models\ProjectProposal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserForEdit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        //Allow SuperAdmin
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        $routeProposal = $request->route('proposal');

        $proposal = $routeProposal instanceof ProjectProposal
            ? $routeProposal
            : ProjectProposal::query()->find($routeProposal);

        if (! $proposal) {
            abort(404, 'Proposal not found');
        }

        $dashboard = $proposal->dashboard; // may be null if dashboard not created
        if (! $dashboard) {
            abort(403, 'Unauthorized');
        }
        $allowed_roles_I = [$dashboard?->user_id, $dashboard?->vice_id, $dashboard?->fo_id];
        $unitHeadsRaw = $dashboard->unit_heads;
        $allowed_roles_II = is_array($unitHeadsRaw) ? $unitHeadsRaw : (empty($unitHeadsRaw) ? [] : [$unitHeadsRaw]);
        $allowed_roles = array_filter(array_merge($allowed_roles_I, $allowed_roles_II)); // Remove null values

        if(in_array($user->id, $allowed_roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
