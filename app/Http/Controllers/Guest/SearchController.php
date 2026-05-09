<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\GuestSearchRequest;
use App\Models\LinkList;
use App\Models\Tag;
use App\Search\LinkSearchScope;
use Illuminate\Contracts\View\View;

class SearchController extends Controller
{
    use SearchesLinks;

    protected function linkSearchScope(): LinkSearchScope
    {
        return LinkSearchScope::publicOnly();
    }

    public function search(GuestSearchRequest $request): View
    {
        $performedSearch = $request->filled('query')
            || $request->filled('only_lists')
            || $request->filled('only_tags');

        if ($performedSearch) {
            $results = $this->searchLinkResults($request);
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
