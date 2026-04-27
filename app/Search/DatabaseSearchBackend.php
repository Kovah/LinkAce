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
    public function searchLinks(SearchQuery $query): LengthAwarePaginator
    {
        return $this->buildLinkQuery($query)->paginate(getPaginationLimit());
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

    private function buildLinkQuery(SearchQuery $query): Builder
    {
        $search = Link::visibleForUser()->with(['tags']);

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
            $search->whereHas('lists', function (Builder $queryBuilder) use ($query) {
                $queryBuilder->whereIn('id', $query->lists);
            });
        }

        if ($query->emptyTags) {
            $search->doesntHave('tags');
        } elseif ($query->tags !== []) {
            $search->whereHas('tags', function (Builder $queryBuilder) use ($query) {
                $queryBuilder->whereIn('id', $query->tags);
            });
        }

        if ($query->orderBy === 'random') {
            $search->inRandomOrder();
        } elseif ($query->orderBy !== null) {
            $search->orderBy(...explode(':', $query->orderBy));
        }

        return $search;
    }
}
