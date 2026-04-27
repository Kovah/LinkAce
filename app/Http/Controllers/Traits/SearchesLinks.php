<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\SearchRequest;
use App\Models\Link;
use App\Search\DatabaseSearchBackend;
use App\Search\SearchQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

trait SearchesLinks
{
    protected string|null $searchQuery = null;
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

    protected function searchLinkResults(SearchRequest $request): LengthAwarePaginator
    {
        $query = SearchQuery::fromRequest($request);
        $this->applySearchState($query);

        return app(DatabaseSearchBackend::class)->searchLinks($query);
    }

    protected function applySearchState(SearchQuery $query): void
    {
        $this->searchQuery = $query->query;
        $this->searchTitle = $query->searchTitle;
        $this->searchDescription = $query->searchDescription;
        $this->searchVisibility = $query->visibility;
        $this->searchBrokenOnly = $query->brokenOnly;
        $this->searchLists = $query->lists;
        $this->searchTags = $query->tags;
        $this->emptyLists = $query->emptyLists;
        $this->emptyTags = $query->emptyTags;
        $this->searchOrderBy = $query->orderBy;
    }

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
     * creates a database query builder for the Link model based on those fields.
     */
    protected function buildDatabaseQuery(SearchRequest $request): Builder
    {
        $query = SearchQuery::fromRequest($request);
        $this->applySearchState($query);

        $search = $this->baseQuery();

        if ($query->hasTextQuery()) {
            $escapedQuery = '%' . escapeSearchQuery($query->query) . '%';
            $search->where(function (Builder $search) use ($query, $escapedQuery) {
                $search->where('url', 'like', $escapedQuery);

                if ($query->searchTitle) {
                    $search->orWhere('title', 'like', $escapedQuery);
                }

                if ($query->searchDescription) {
                    $search->orWhere('description', 'like', $escapedQuery);
                }
            });
        }

        if ($query->visibility !== null) {
            $search->where('visibility', $query->visibility);
        }

        if ($query->brokenOnly) {
            $search->where('status', '>', 1);
        }

        if ($query->emptyLists) {
            $search->doesntHave('lists');
        } elseif ($query->lists !== []) {
            $this->filterByLists($search, $query->lists);
        }

        if ($query->emptyTags) {
            $search->doesntHave('tags');
        } elseif ($query->tags !== []) {
            $this->filterByTags($search, $query->tags);
        }

        if ($query->orderBy === 'random') {
            $search->inRandomOrder();
        } elseif ($query->orderBy !== null) {
            $search->orderBy(...explode(':', $query->orderBy));
        }

        return $search;
    }

    /**
     * Restrict results to links attached to one of the given list IDs.
     * Override to scope the relation to a different visibility.
     */
    protected function filterByLists(Builder $search, array $listIds): void
    {
        $search->whereHas('lists', function (Builder $query) use ($listIds) {
            $query->whereIn('id', $listIds);
        });
    }

    /**
     * Restrict results to links attached to one of the given tag IDs.
     * Override to scope the relation to a different visibility.
     */
    protected function filterByTags(Builder $search, array $tagIds): void
    {
        $search->whereHas('tags', function (Builder $query) use ($tagIds) {
            $query->whereIn('id', $tagIds);
        });
    }
}
