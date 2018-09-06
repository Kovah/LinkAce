<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Database\Query\Builder;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearch() {
        return view('actions.search.search')
            ->with('results', collect([]))
            ->with('query_settings', [
                'old_query' => null,
                'search_title' => false,
                'search_description' => false,
                'private_only' => false,
                'only_category' => 0,
                'only_tag' => 0,
            ])
            ->with('categories', Category::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get())
            ->with('tags', Tag::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get()
            );
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doSearch(SearchRequest $request)
    {
        // Get the query
        $raw_query = $request->get('query');
        $query = '%' . $raw_query . '%';

        // Start building the search
        $search = Link::byUser(auth()->user()->id)
            ->where('url', 'like', $query);

        // Also search for the title if applicable
        if ($search_title = $request->get('search_title', false)) {
            $search->orWhere('title', 'like', $query);
        }

        // Also search for the title if applicable
        if ($search_description = $request->get('search_description', false)) {
            $search->orWhere('description', 'like', $query);
        }

        // Show private only if applicable
        if ($private_only = $request->get('private_only', false)) {
            $search->where('is_private', true);
        }

        // Show by specific category only if applicable
        if ($category_id = $request->get('only_category', false)) {
            $search->whereHas('category', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            });
        }

        // Show by specific tag only if applicable
        if ($tag_id = $request->get('only_tag', false)) {
            $search->whereHas('tags', function ($query) use ($tag_id) {
                $query->where('id', $tag_id);
            });
        }

        // Order the results if applicable
        if ($orderby_column = $request->get('orderby_column', '')) {
            $direction = $request->get('orderby_direction', 'ASC');
            $search->orderBy($orderby_column, $direction);
        }

        // Get the results
        $results = $search->paginate(config('linkace.default.pagination'));

        return view('actions.search.search')
            ->with('results', $results)
            ->with('query_settings', [
                'old_query' => $raw_query,
                'search_title' => $search_title,
                'search_description' => $search_description,
                'private_only' => $private_only,
                'only_category' => $category_id,
                'only_tag' => $tag_id,
            ])
            ->with('categories', Category::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get())
            ->with('tags', Tag::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get()
            );
    }
}
