<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\SearchRequest;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;

trait SearchesLinks
{
    protected $searchQuery;
    protected $searchTitle;
    protected $searchDescription;
    protected $searchPrivateOnly;
    protected $searchBrokenOnly;
    protected $searchLists;
    protected $searchTags;
    protected $emptyLists;
    protected $emptyTags;
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

    /**
     * This method takes a HTTP request containing various search fields and
     * create a database query builder for the Link model based on that fields.
     *
     * @param SearchRequest $request
     * @return Builder
     */
    protected function buildDatabaseQuery(SearchRequest $request): Builder
    {
        // Start building the search
        $search = Link::byUser($request->user()->id);

        // Search for the URL
        if ($this->searchQuery = $request->input('query', false)) {
            $query = '%' . escapeSearchQuery($this->searchQuery) . '%';
            $search->where(function ($search) use ($request, $query) {
                $search->where('url', 'like', $query);

                // Also search for the title if applicable
                if ($this->searchTitle = $request->input('search_title', false)) {
                    $search->orWhere('title', 'like', $query);
                }

                // Also search for the title if applicable
                if ($this->searchDescription = $request->input('search_description', false)) {
                    $search->orWhere('description', 'like', $query);
                }
            });
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
        if ($this->emptyLists = $request->input('empty_lists', false)) {
            $search->doesntHave('lists');
        } elseif ($this->searchLists = $request->input('only_lists', false)) {
            $search->whereHas('lists', function ($query) use ($request) {
                $field = $request->isJson() ? 'id' : 'name';
                $query->whereIn($field, explode(',', $this->searchLists));
            });
        }

        // Show by specific tag only if applicable
        if ($this->emptyTags = $request->input('empty_tags', false)) {
            $search->doesntHave('tags');
        } elseif ($this->searchTags = $request->input('only_tags', false)) {
            $search->whereHas('tags', function ($query) use ($request) {
                $field = $request->isJson() ? 'id' : 'name';
                $query->whereIn($field, explode(',', $this->searchTags));
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
