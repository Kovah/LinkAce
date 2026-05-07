<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\GuestSearchRequest;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    use SearchesLinks;

    protected function baseQuery(): Builder
    {
        return Link::publicOnly()->with(['tags' => fn ($query) => $query->publicOnly()]);
    }

    protected function filterByLists(Builder $search, array $listIds): void
    {
        $search->whereHas('lists', function ($query) use ($listIds) {
            $query->whereIn('id', $listIds)->publicOnly();
        });
    }

    protected function filterByTags(Builder $search, array $tagIds): void
    {
        $search->whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('id', $tagIds)->publicOnly();
        });
    }

    public function search(GuestSearchRequest $request): View
    {
        $performedSearch = $request->filled('query')
            || $request->filled('only_lists')
            || $request->filled('only_tags');

        if ($performedSearch) {
            $results = $this->buildDatabaseQuery($request)->paginate(getPaginationLimit());
        } else {
            $results = collect([]);
        }

        return view('guest.search.search', [
            'pageTitle' => $performedSearch
                ? trans('search.results_for') . ' ' . $this->searchQuery
                : trans('search.search'),
            'all_tags' => Tag::publicOnly()->get(['name', 'id']),
            'all_lists' => LinkList::publicOnly()->get(['name', 'id']),
        ])
            ->with('results', $results)
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => $performedSearch ? $this->searchQuery : null,
                'search_title' => $performedSearch ? $this->searchTitle : true,
                'search_description' => $performedSearch ? $this->searchDescription : true,
                'only_lists' => $performedSearch ? $this->searchLists : [],
                'only_tags' => $performedSearch ? $this->searchTags : [],
                'order_by' => ($performedSearch && $this->searchOrderBy) ? $this->searchOrderBy : $this->orderByOptions[0],
                'performed_search' => $performedSearch,
            ]);
    }
}
