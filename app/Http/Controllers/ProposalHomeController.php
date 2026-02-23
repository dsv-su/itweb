<?php

namespace App\Http\Controllers;

use App\Models\SettingsOh;
use App\Services\Role\RoleHandler;

class ProposalHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth', 'dsv']);
    }

    public function pp(string $slug = 'my')
    {
        // lighter query + safe if no record exists
        $enabled = SettingsOh::query()->value('form_enable') ?? false;

        // $enabled = Cache::remember('settings.form_enable', 60, fn () => SettingsOh::query()->value('form_enable') ?? false);

        if (!$enabled) {
            return (new \Statamic\View\View)
                ->template('pp.disabled')
                ->with(['breadcrumb' => 'Disabled']);
        }

        $roles = (new RoleHandler(auth()->user()))->show();

        $breadcrumbs = [
            'my'       => 'My proposals',
            'awaiting' => 'Awaiting review',
            'all'      => 'Proposals',
        ];

        return (new \Statamic\View\View)
            ->template('pp.index')
            ->with([
                'page'       => $slug,
                'breadcrumb' => $breadcrumbs[$slug] ?? 'Unknown',
                'roles'      => $roles,
            ]);
    }
}
