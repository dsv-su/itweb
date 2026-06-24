<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Facades\Search as StatamicSearch;
use Statamic\Facades\Site;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $collection = (string) $request->query('collection', 'all');
        $sort = (string) $request->query('sort', 'relevance');
        $collections = $this->indexedCollections();

        if (! $collections->has($collection)) {
            $collection = 'all';
        }

        if (! in_array($sort, ['relevance', 'title'], true)) {
            $sort = 'relevance';
        }

        $results = $q === ''
            ? collect()
            : StatamicSearch::index(config('statamic.search.default'))
                ->ensureExists()
                ->search($q)
                ->where('site', Site::current()->handle)
                ->get()
                ->take(100)
                ->map(fn ($result) => $this->normalizeResult($result));

        $filterCounts = $results
            ->groupBy('collection')
            ->map(fn (Collection $items) => $items->count());

        $filteredResults = $results
            ->when($collection !== 'all', fn (Collection $items) => $items->where('collection', $collection))
            ->when($sort === 'title', fn (Collection $items) => $items->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE))
            ->values();

        return view('search.index', [
            'q' => $q,
            'collection' => $collection,
            'sort' => $sort,
            'collections' => $collections,
            'filterCounts' => $filterCounts,
            'results' => $filteredResults,
            'totalResults' => $results->count(),
            'title' => __('Search'),
        ]);
    }

    private function normalizeResult($result): array
    {
        $data = $result->toAugmentedCollection()->toArray();

        $title = $this->value($data['title'] ?? null);
        $url = $this->value($data['url'] ?? null);
        $text = $this->value($data['text_field'] ?? ($data['content'] ?? null));
        $collection = $this->collectionHandle($data['collection'] ?? null);

        return [
            'title' => $title !== '' ? $title : __('Untitled'),
            'url' => $url,
            'collection' => $collection,
            'collection_label' => $this->collectionLabel($collection),
            'excerpt' => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($text))), 220),
            'score' => (int) ($data['search_score'] ?? 0),
        ];
    }

    private function indexedCollections(): Collection
    {
        $searchables = config('statamic.search.indexes.' . config('statamic.search.default') . '.searchables', []);

        return collect($searchables)
            ->filter(fn ($searchable) => str_starts_with((string) $searchable, 'collection:'))
            ->mapWithKeys(function ($searchable) {
                $handle = Str::after((string) $searchable, 'collection:');

                return [$handle => $this->collectionLabel($handle)];
            })
            ->prepend(__('All content'), 'all');
    }

    private function collectionHandle($value): string
    {
        $value = $this->value($value);

        return $value !== '' ? $value : 'pages';
    }

    private function collectionLabel(string $handle): string
    {
        return match ($handle) {
            'all' => __('All content'),
            'pages' => __('Pages'),
            'equipment' => __('Equipment'),
            'it' => __('IT'),
            'itnews' => __('IT news'),
            'network' => __('Network'),
            'software' => __('Software'),
            default => Str::headline($handle),
        };
    }

    private function value($value): string
    {
        if (is_object($value) && method_exists($value, 'value')) {
            $value = $value->value();
        }

        if (is_object($value) && method_exists($value, 'handle')) {
            return (string) $value->handle();
        }

        if (is_object($value) && method_exists($value, 'url')) {
            return (string) $value->url();
        }

        if (is_array($value)) {
            return collect($value)->flatten()->implode(' ');
        }

        return trim((string) $value);
    }
}
