<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /** @var array */
    public $order_by_options = [
        'title:asc',
        'title:desc',
        'url:asc',
        'url:desc',
        'created_at:asc',
        'created_at:desc',
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearch()
    {
        return view('actions.search.search')
            ->with('results', collect([]))
            ->with('order_by_options', $this->order_by_options)
            ->with('query_settings', [
                'old_query' => null,
                'search_title' => false,
                'search_description' => false,
                'private_only' => false,
                'only_category' => 0,
                'only_tags' => '',
                'order_by' => $this->order_by_options[0],
            ])
            ->with('categories', Category::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get());
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doSearch(SearchRequest $request)
    {
        $search_title = false;
        $search_description = false;

        // Start building the search
        $search = Link::byUser(auth()->id());

        // Search for the URL
        if ($raw_query = $request->get('query', false)) {
            $query = '%' . $raw_query . '%';
            $search->where('url', 'like', $query);

            // Also search for the title if applicable
            if ($search_title = $request->get('search_title', false)) {
                $search->orWhere('title', 'like', $query);
            }

            // Also search for the title if applicable
            if ($search_description = $request->get('search_description', false)) {
                $search->orWhere('description', 'like', $query);
            }
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
        if ($tag_names = $request->get('only_tags', false)) {
            $search->whereHas('tags', function ($query) use ($tag_names) {
                $query->whereIn('name', explode(',', $tag_names));
            });
        }

        // Order the results if applicable
        if ($orderby = $request->get('orderby', $this->order_by_options[0])) {
            $order_by = explode(':', $orderby);
            $search->orderBy($order_by[0], $order_by[1]);
        }

        // Get the results
        $results = $search->paginate(getPaginationLimit());

        return view('actions.search.search')
            ->with('results', $results)
            ->with('order_by_options', $this->order_by_options)
            ->with('query_settings', [
                'old_query' => $raw_query,
                'search_title' => $search_title,
                'search_description' => $search_description,
                'private_only' => $private_only,
                'only_category' => $category_id,
                'only_tags' => $tag_names,
                'order_by' => $orderby,
            ])
            ->with('categories', Category::byUser(auth()->user()->id)
                ->orderBy('name', 'asc')->get());
    }
}
