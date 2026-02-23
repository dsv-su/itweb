<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Statamic\View\View as StatamicView;
use Statamic\Statamic;

class NewsListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checklang', 'locale']);
    }

    public function list(string $collection)
    {
        return $this->renderList($collection);
    }

    public function swelist(string $collection)
    {
        App::setLocale('sv');
        return $this->renderList($collection);
    }

    private function renderList(string $collection)
    {
        // Basic hardening: only allow letters/numbers/dash/underscore
        abort_unless(preg_match('/^[a-z0-9\-_]+$/i', $collection), 404);

        $collections = Statamic::tag('collection:' . $collection)
            ->where('collection', $collection)
            ->fetch();

        return (new StatamicView)
            ->template('news.list')
            //->layout('mylayout')
            ->with([
                'type' => 'news',
                'collections' => $collections,
            ]);
    }
}
