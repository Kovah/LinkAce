<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\SearchRequest;
use Illuminate\Contracts\View\View;

class SearchController extends Controller
{
    use SearchesLinks;

    /**
     * Display the initial search form.
     *
     * @return View
     */
    public function getSearch(): View
    {
        return view('app.search.search')
            ->with('results', collect([]))
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => null,
                'search_title' => true,
                'search_description' => true,
                'private_only' => false,
                'broken_only' => false,
                'empty_tags' => false,
                'empty_lists' => false,
                'only_lists' => '',
                'only_tags' => '',
                'order_by' => $this->orderByOptions[0],
            ]);
    }

    /**
     * Handle the search query and display the view with all results.
     *
     * @param SearchRequest $request
     * @return View
     */
    public function doSearch(SearchRequest $request): View
    {
        $search = $this->buildDatabaseQuery($request);
        $results = $search->paginate(getPaginationLimit());

        return view('app.search.search')
            ->with('results', $results)
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => $this->searchQuery,
                'search_title' => $this->searchTitle,
                'search_description' => $this->searchDescription,
                'private_only' => $this->searchPrivateOnly,
                'broken_only' => $this->searchBrokenOnly,
                'only_lists' => $this->searchLists,
                'only_tags' => $this->searchTags,
                'empty_tags' => $this->emptyTags,
                'empty_lists' => $this->emptyLists,
                'order_by' => $this->searchOrderBy,
            ]);
    }
}
