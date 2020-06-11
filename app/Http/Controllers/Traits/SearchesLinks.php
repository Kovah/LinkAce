<?php

namespace App\Http\Controllers\Traits;

use App\Models\Link;
use Illuminate\Http\Request;

trait SearchesLinks
{
    protected $searchQuery;
    protected $searchTitle;
    protected $searchDescription;
    protected $searchPrivateOnly;
    protected $searchBrokenOnly;
    protected $searchListNames;
    protected $searchTagNames;
    protected $searchOrderBy;

    /** @var array */
    public $orderByOptions = [
        'title:asc',
        'title:desc',
        'url:asc',
        'url:desc',
        'created_at:asc',
        'created_at:desc',
    ];

    public function buildDatabaseQuery(Request $request)
    {
        // Start building the search
        $search = Link::byUser($request->user()->id);

        // Search for the URL
        if ($this->searchQuery = $request->input('query', false)) {
            $query = '%' . $this->searchQuery . '%';
            $search->where('url', 'like', $query);

            // Also search for the title if applicable
            if ($this->searchTitle = $request->input('search_title', false)) {
                $search->orWhere('title', 'like', $query);
            }

            // Also search for the title if applicable
            if ($this->searchDescription = $request->input('search_description', false)) {
                $search->orWhere('description', 'like', $query);
            }
        }

        // Show private only if applicable
        if ($this->searchPrivateOnly = $request->input('private_only', false)) {
            $search->where('is_private', true);
        }

        // Show broken only if applicable
        if ($this->searchBrokenOnly = $request->input('broken_only', false)) {
            $search->where('status', '>', 1);
        }

        // Show by specific list only if applicable
        if ($this->searchListNames = $request->input('only_lists', false)) {
            $search->whereHas('lists', function ($query) {
                $query->whereIn('name', explode(',', $this->searchListNames));
            });
        }

        // Show by specific tag only if applicable
        if ($this->searchTagNames = $request->input('only_tags', false)) {
            $search->whereHas('tags', function ($query) {
                $query->whereIn('name', explode(',', $this->searchTagNames));
            });
        }

        // Order the results if applicable
        if ($this->searchOrderBy = $request->input('order_by', $this->orderByOptions[0])) {
            $search->orderBy(...explode(':', $this->searchOrderBy));
        }

        // Return the query builder itself
        return $search;
    }
}
