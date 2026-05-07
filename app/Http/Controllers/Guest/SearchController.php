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

    public function getSearch(): View
    {
        return view('guest.search.search', [
            'pageTitle' => trans('search.search'),
            'all_tags' => Tag::publicOnly()->get(['name', 'id']),
            'all_lists' => LinkList::publicOnly()->get(['name', 'id']),
        ])
            ->with('results', collect([]))
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => null,
                'search_title' => true,
                'search_description' => true,
                'only_lists' => [],
                'only_tags' => [],
                'order_by' => $this->orderByOptions[0],
                'performed_search' => false,
            ]);
    }

    public function doSearch(GuestSearchRequest $request): View
    {
        $search = $this->buildDatabaseQuery($request);
        $results = $search->paginate(getPaginationLimit());

        return view('guest.search.search', [
            'pageTitle' => trans('search.results_for') . ' ' . $this->searchQuery,
            'all_tags' => Tag::publicOnly()->get(['name', 'id']),
            'all_lists' => LinkList::publicOnly()->get(['name', 'id']),
        ])
            ->with('results', $results)
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => $this->searchQuery,
                'search_title' => $this->searchTitle,
                'search_description' => $this->searchDescription,
                'only_lists' => $this->searchLists,
                'only_tags' => $this->searchTags,
                'order_by' => $this->searchOrderBy,
                'performed_search' => true,
            ]);
    }
}
