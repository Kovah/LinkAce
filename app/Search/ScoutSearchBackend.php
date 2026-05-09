<?php

namespace App\Search;

use App\Exceptions\ExternalSearchUnavailableException;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Builder as ScoutBuilder;
use Throwable;

class ScoutSearchBackend implements SearchBackend
{
    public function __construct(private readonly DatabaseSearchBackend $databaseSearch)
    {
    }

    public function searchLinks(SearchQuery $query, ?LinkSearchScope $scope = null): LengthAwarePaginator
    {
        $scope ??= LinkSearchScope::visibleForUser();

        if ($query->orderBy === 'random' || ! $query->hasTextQuery()) {
            return $this->databaseSearch->searchLinks($query, $scope);
        }

        return $this->withExternalSearchErrors(function () use ($query, $scope) {
            $builder = Link::search($query->query)
                ->options($this->searchOptions($query->searchableLinkAttributes()))
                ->query(function ($builder) use ($query, $scope) {
                    $scope->applyBaseConstraints($builder);
                    $this->databaseSearch->applyLinkFilters($builder, $query, $scope);
                });

            $this->applyLinkFilters($builder, $query, $scope);
            $this->applyLinkOrdering($builder, $query);

            return $builder->paginate(getPaginationLimit());
        }, ['query' => $query->query, 'model' => Link::class]);
    }

    public function searchTags(Request $request): Collection
    {
        $query = $request->input('query', false);

        if (! $query) {
            return collect([]);
        }

        return $this->withExternalSearchErrors(function () use ($request, $query) {
            $userId = $request->user()?->id ?? auth()->id();

            return Tag::search($query)
                ->where('user_id', $userId)
                ->options($this->searchOptions(['name']))
                ->query(fn ($builder) => $builder->byUser($userId)->oldest('name'))
                ->get()
                ->pluck('name', 'id');
        }, ['query' => $query, 'model' => Tag::class]);
    }

    public function searchLists(Request $request): Collection
    {
        $query = $request->input('query', false);

        if (! $query) {
            return collect([]);
        }

        return $this->withExternalSearchErrors(function () use ($request, $query) {
            $userId = $request->user()?->id ?? auth()->id();

            return LinkList::search($query)
                ->where('user_id', $userId)
                ->options($this->searchOptions(['name', 'description']))
                ->query(fn ($builder) => $builder->byUser($userId)->oldest('name'))
                ->get()
                ->pluck('name', 'id');
        }, ['query' => $query, 'model' => LinkList::class]);
    }

    private function applyLinkFilters(ScoutBuilder $builder, SearchQuery $query, LinkSearchScope $scope): void
    {
        $scope->applyEngineFilters($builder);

        if ($query->visibility !== null) {
            $builder->where('visibility', $query->visibility);
        }

        if ($query->brokenOnly) {
            $builder->where('status', '>', 1);
        }

        if ($query->lists !== []) {
            $builder->whereIn('list_ids', $query->lists);
        }

        if ($query->tags !== []) {
            $builder->whereIn('tag_ids', $query->tags);
        }

        if ($query->emptyLists) {
            $builder->where('lists_count', 0);
        }

        if ($query->emptyTags) {
            $builder->where('tags_count', 0);
        }
    }

    private function applyLinkOrdering(ScoutBuilder $builder, SearchQuery $query): void
    {
        if ($query->orderBy === null) {
            return;
        }

        [$column, $direction] = explode(':', $query->orderBy);
        $builder->orderBy($column, $direction);
    }

    private function searchOptions(array $attributes): array
    {
        return match (config('linkace.search.driver')) {
            'meilisearch' => [
                'attributesToSearchOn' => $attributes,
            ],
            'typesense' => [
                'query_by' => implode(',', $attributes),
                'split_join_tokens' => 'fallback',
                'drop_tokens_threshold' => 0,
            ],
            default => [],
        };
    }

    private function withExternalSearchErrors(callable $callback, array $context): mixed
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            Log::error('External search failed.', [
                ...$context,
                'driver' => config('linkace.search.driver'),
                'exception' => $exception,
            ]);

            throw new ExternalSearchUnavailableException($exception);
        }
    }
}
