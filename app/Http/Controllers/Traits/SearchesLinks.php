<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\SearchRequest;
use App\Search\LinkSearchScope;
use App\Search\SearchBackendManager;
use App\Search\SearchQuery;
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
    protected string $searchListMode = 'any';
    protected string $searchTagMode = 'any';
    protected array $searchExcludeLists = [];
    protected array $searchExcludeTags = [];
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

        return app(SearchBackendManager::class)->backend()->searchLinks($query, $this->linkSearchScope());
    }

    protected function linkSearchScope(): LinkSearchScope
    {
        return LinkSearchScope::visibleForUser();
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
        $this->searchListMode = $query->listMode;
        $this->searchTagMode = $query->tagMode;
        $this->searchExcludeLists = $query->excludeLists;
        $this->searchExcludeTags = $query->excludeTags;
        $this->emptyLists = $query->emptyLists;
        $this->emptyTags = $query->emptyTags;
        $this->searchOrderBy = $query->orderBy;
    }
}
