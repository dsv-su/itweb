<?php

namespace App\Http\Controllers;

use Statamic\Facades\Collection;
use Statamic\View\View as StatamicView;
use Statamic\Statamic;

class NewsListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checklang']);
    }

    public function list(string $lang, string $collection)
    {
        return $this->renderList($collection);
    }

    private function renderList(string $collection)
    {
        // Basic hardening: only allow letters/numbers/dash/underscore
        abort_unless(preg_match('/^[a-z0-9\-_]+$/i', $collection), 404);

        abort_unless(Collection::findByHandle($collection), 404);

        $collections = Statamic::tag('collection:' . $collection)
            ->where('collection', $collection)
            ->sort('date:desc')
            ->fetch();

        return (new StatamicView)
            ->template('news.list')
            ->with([
                'type' => 'news',
                'collections' => $collections,
            ]);
    }
}
