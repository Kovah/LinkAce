<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\SearchRequest;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;

trait SearchesLinks
{
    protected string|null $searchQuery;
    protected bool $searchTitle = false;
    protected bool $searchDescription = false;
    protected ?int $searchVisibility = null;
    protected bool $searchBrokenOnly = false;
    protected string|null $searchLists = null;
    protected string|null $searchTags = null;
    protected bool $emptyLists = false;
    protected bool $emptyTags = false;
    protected string|null $searchOrderBy = null;

    public array $orderByOptions = [
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
        $search = Link::byUser($request->user()->id)->with(['tags']);

        // Search for the URL
        if ($this->searchQuery = $request->input('query')) {
            $query = '%' . escapeSearchQuery($this->searchQuery) . '%';
            $search->where(function ($search) use ($request, $query) {
                $search->where('url', 'like', $query);

                // Also search for the title if applicable
                if ($this->searchTitle = (bool)$request->input('search_title', false)) {
                    $search->orWhere('title', 'like', $query);
                }

                // Also search for the description if applicable
                if ($this->searchDescription = (bool)$request->input('search_description', false)) {
                    $search->orWhere('description', 'like', $query);
                }
            });
        }

        // Show private only if applicable
        if ($this->searchVisibility = $request->input('visibility')) {
            $search->where('visibility', $this->searchVisibility);
        }

        // Show broken only if applicable
        if ($this->searchBrokenOnly = (bool)$request->input('broken_only', false)) {
            $search->where('status', '>', 1);
        }

        // Show by specific list only if applicable
        if ($this->emptyLists = (bool)$request->input('empty_lists', false)) {
            $search->doesntHave('lists');
        } elseif ($this->searchLists = $request->input('only_lists')) {
            $search->whereHas('lists', function ($query) use ($request) {
                $field = $request->isJson() ? 'id' : 'name';
                $query->whereIn($field, explode(',', $this->searchLists));
            });
        }

        // Show by specific tag only if applicable
        if ($this->emptyTags = (bool)$request->input('empty_tags', false)) {
            $search->doesntHave('tags');
        } elseif ($this->searchTags = $request->input('only_tags')) {
            $search->whereHas('tags', function ($query) use ($request) {
                $field = $request->isJson() ? 'id' : 'name';
                $query->whereIn($field, explode(',', $this->searchTags));
            });
        }

        // Order the results if applicable and only allow predefined ordering
        if ($this->searchOrderBy = $request->input('order_by')) {
            if ($this->searchOrderBy === 'random') {
                $search->inRandomOrder();
            } else {
                $this->searchOrderBy = in_array($this->searchOrderBy, $this->orderByOptions)
                    ? $this->searchOrderBy : $this->orderByOptions[0];
            }
            $search->orderBy(...explode(':', $this->searchOrderBy));
        }

        // Return the query builder itself
        return $search;
    }
}
