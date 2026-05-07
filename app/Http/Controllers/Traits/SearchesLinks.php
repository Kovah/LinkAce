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
    protected array $searchLists = [];
    protected array $searchTags = [];
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
     * The starting point of the search query. Override this in a controller
     * to scope the search to a different visibility (e.g. public only for guests).
     */
    protected function baseQuery(): Builder
    {
        return Link::visibleForUser()->with(['tags']);
    }

    /**
     * This method takes a HTTP request containing various search fields and
     * create a database query builder for the Link model based on that fields.
     *
     * @param SearchRequest $request
     * @return Builder
     */
    protected function buildDatabaseQuery(SearchRequest $request): Builder
    {
        $search = $this->baseQuery();

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
        } elseif ($request->input('only_lists')) {
            $lists = $request->input('only_lists', '[]');
            $this->searchLists = preg_match('/\[.*\]/', $lists) > 0 ? json_decode($lists) : explode(',', $lists);
            if (!empty($this->searchLists)) {
                $search->whereHas('lists', function ($query) {
                    $query->whereIn('id', $this->searchLists);
                });
            }
        }

        // Show by specific tag only if applicable
        if ($this->emptyTags = (bool)$request->input('empty_tags', false)) {
            $search->doesntHave('tags');
        } elseif ($request->input('only_tags')) {
            $tags = $request->input('only_tags', '[]');
            $this->searchTags = preg_match('/\[.*\]/', $tags) > 0 ? json_decode($tags) : explode(',', $tags);
            if (!empty($this->searchTags)) {
                $search->whereHas('tags', function ($query) {
                    $query->whereIn('id', $this->searchTags);
                });
            }
        }

        // Order the results if applicable and only allow predefined ordering
        if ($this->searchOrderBy = $request->input('order_by')) {
            if ($this->searchOrderBy === 'random') {
                $search->inRandomOrder();
            } else {
                $this->searchOrderBy = in_array($this->searchOrderBy, $this->orderByOptions)
                    ? $this->searchOrderBy
                    : $this->orderByOptions[0];
            }
            $search->orderBy(...explode(':', $this->searchOrderBy));
        }

        // Return the query builder itself
        return $search;
    }
}
