<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\AppSearchRequest;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Contracts\View\View;

class SearchController extends Controller
{
    use SearchesLinks;

    public function search(AppSearchRequest $request): View
    {
        $performedSearch = $request->filled('query')
            || $request->filled('only_lists')
            || $request->filled('only_tags')
            || $request->filled('exclude_lists')
            || $request->filled('exclude_tags')
            || $request->filled('broken_only')
            || $request->filled('empty_tags')
            || $request->filled('empty_lists');

        if ($performedSearch) {
            $results = $this->searchLinkResults($request);
        } else {
            $results = collect([]);
        }

        return view('app.search.search', [
            'pageTitle' => $performedSearch
                ? trans('search.results_for') . ' ' . $this->searchQuery
                : trans('search.search'),
            'all_tags' => Tag::visibleForUser()->with('user:id,name')->get(['name', 'id', 'user_id']),
            'all_lists' => LinkList::visibleForUser()->with('user:id,name')->get(['name', 'id', 'user_id']),
        ])
            ->with('results', $results)
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => $performedSearch ? $this->searchQuery : null,
                'search_title' => $performedSearch ? $this->searchTitle : true,
                'search_description' => $performedSearch ? $this->searchDescription : true,
                'visibility' => $performedSearch ? $this->searchVisibility : null,
                'broken_only' => $performedSearch ? $this->searchBrokenOnly : false,
                'only_lists' => $performedSearch ? $this->searchLists : [],
                'only_tags' => $performedSearch ? $this->searchTags : [],
                'list_mode' => $performedSearch ? $this->searchListMode : 'all',
                'tag_mode' => $performedSearch ? $this->searchTagMode : 'all',
                'exclude_lists' => $performedSearch ? $this->searchExcludeLists : [],
                'exclude_tags' => $performedSearch ? $this->searchExcludeTags : [],
                'empty_tags' => $performedSearch ? $this->emptyTags : false,
                'empty_lists' => $performedSearch ? $this->emptyLists : false,
                'order_by' => ($performedSearch && $this->searchOrderBy) ? $this->searchOrderBy : $this->orderByOptions[0],
                'performed_search' => $performedSearch,
            ]);
    }
}
