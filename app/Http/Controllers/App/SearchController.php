<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Link;
use App\Models\LinkList;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers\App
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
     * @return Factory|View
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
                'only_lists' => '',
                'only_tags' => '',
                'order_by' => $this->order_by_options[0],
            ]);
    }

    /**
     * @param SearchRequest $request
     * @return Factory|View
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

        // Show by specific list only if applicable
        if ($list_names = $request->get('only_lists', false)) {
            $search->whereHas('lists', function ($query) use ($list_names) {
                $query->whereIn('name', explode(',', $list_names));
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
                'only_lists' => $list_names,
                'only_tags' => $tag_names,
                'order_by' => $orderby,
            ]);
    }
}
