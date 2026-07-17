<?php

namespace App\Search;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DatabaseSearchBackend implements SearchBackend
{
    public function searchLinks(SearchQuery $query, ?LinkSearchScope $scope = null): LengthAwarePaginator
    {
        return $this->buildLinkQuery($query, $scope ?? LinkSearchScope::visibleForUser())->paginate(getPaginationLimit());
    }

    public function searchTags(Request $request): Collection
    {
        $query = $request->input('query', false);

        if (!$query) {
            return collect([]);
        }

        return Tag::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->oldest('name')
            ->pluck('name', 'id');
    }

    public function searchLists(Request $request): Collection
    {
        $query = $request->input('query', false);

        if (!$query) {
            return collect([]);
        }

        return LinkList::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->oldest('name')
            ->pluck('name', 'id');
    }

    private function buildLinkQuery(SearchQuery $query, LinkSearchScope $scope): Builder
    {
        $search = $scope->baseQuery();

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

        $this->applyLinkFilters($search, $query, $scope);

        if ($query->orderBy === 'random') {
            $search->inRandomOrder();
        } elseif ($query->orderBy !== null) {
            $search->orderBy(...explode(':', $query->orderBy));
        }

        return $search;
    }

    public function applyLinkFilters(Builder $search, SearchQuery $query, LinkSearchScope $scope): Builder
    {
        if ($query->visibility !== null) {
            $search->where('visibility', $query->visibility);
        }

        if ($query->brokenOnly) {
            $search->where('status', '>', 1);
        }

        if ($query->emptyLists) {
            $search->doesntHave('lists');
        } elseif ($query->lists !== []) {
            $scope->filterByLists($search, $query->lists, $query->listMode);
        }

        if ($query->excludeLists !== []) {
            $scope->excludeLists($search, $query->excludeLists);
        }

        if ($query->emptyTags) {
            $search->doesntHave('tags');
        } elseif ($query->tags !== []) {
            $scope->filterByTags($search, $query->tags, $query->tagMode);
        }

        if ($query->excludeTags !== []) {
            $scope->excludeTags($search, $query->excludeTags);
        }

        return $search;
    }
}
