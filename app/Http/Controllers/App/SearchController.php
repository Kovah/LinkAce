<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\SearchRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers\App
 */
class SearchController extends Controller
{
    use SearchesLinks;

    /**
     * @return Factory|View
     */
    public function getSearch()
    {
        return view('actions.search.search')
            ->with('results', collect([]))
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => null,
                'search_title' => false,
                'search_description' => false,
                'private_only' => false,
                'broken_only' => false,
                'only_lists' => '',
                'only_tags' => '',
                'order_by' => $this->orderByOptions[0],
            ]);
    }

    /**
     * @param SearchRequest $request
     * @return Factory|View
     */
    public function doSearch(SearchRequest $request)
    {
        $search = $this->buildDatabaseQuery($request);
        $results = $search->paginate(getPaginationLimit());

        return view('actions.search.search')
            ->with('results', $results)
            ->with('order_by_options', $this->orderByOptions)
            ->with('query_settings', [
                'old_query' => $this->searchQuery,
                'search_title' => $this->searchTitle,
                'search_description' => $this->searchDescription,
                'private_only' => $this->searchPrivateOnly,
                'broken_only' => $this->searchBrokenOnly,
                'only_lists' => $this->searchListNames,
                'only_tags' => $this->searchTagNames,
                'order_by' => $this->searchOrderBy,
            ]);
    }
}
