<?php

namespace App\Http\Middleware;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\SettingsVice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserForView
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

        // Allow SuperAdmin
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

        if (in_array($user->id, $allowed_roles)) {
            return $next($request);
        }

        if ($this->isConfiguredNotificationRecipient($user->email, $dashboard)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }

    private function isConfiguredNotificationRecipient(?string $email, Dashboard $dashboard): bool
    {
        if (! $email) {
            return false;
        }

        $settings = SettingsVice::first();
        if (! $settings) {
            return false;
        }

        $state = (string) $dashboard->state;
        $recipients = [];

        if (in_array($state, ['sent', 'granted', 'denied'], true)) {
            $recipients = [
                ...$recipients,
                ...($settings->sent_notification_recipients ?? []),
            ];
        }

        if ($state === 'granted') {
            $recipients = [
                ...$recipients,
                ...($settings->grant_notification_recipients ?? []),
            ];
        }

        $recipientEmails = collect($recipients)
            ->pluck('email')
            ->filter()
            ->map(fn ($recipientEmail) => strtolower((string) $recipientEmail));

        return $recipientEmails->contains(strtolower($email));
    }
}
