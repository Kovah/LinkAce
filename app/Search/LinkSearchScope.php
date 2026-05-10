<?php

namespace App\Search;

use App\Enums\ModelAttribute;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Builder as ScoutBuilder;

class LinkSearchScope
{
    private function __construct(private readonly bool $publicOnly)
    {
    }

    public static function visibleForUser(): self
    {
        return new self(false);
    }

    public static function publicOnly(): self
    {
        return new self(true);
    }

    public function baseQuery(): Builder
    {
        return $this->applyBaseConstraints(Link::query());
    }

    public function applyBaseConstraints(Builder $query): Builder
    {
        if ($this->publicOnly) {
            return $query->publicOnly()
                ->with(['tags' => fn ($query) => $query->publicOnly()]);
        }

        return $query->visibleForUser()->with(['tags']);
    }

    public function filterByLists(Builder $query, array $listIds, string $mode = 'any'): Builder
    {
        if ($mode === 'all') {
            foreach ($listIds as $listId) {
                $query->whereHas('lists', function ($query) use ($listId) {
                    $query->where('id', $listId);

                    if ($this->publicOnly) {
                        $query->publicOnly();
                    }
                });
            }

            return $query;
        }

        return $query->whereHas('lists', function ($query) use ($listIds) {
            $query->whereIn('id', $listIds);
            $this->applyPublicOnlyConstraint($query);
        });
    }

    public function excludeLists(Builder $query, array $listIds): Builder
    {
        return $query->whereDoesntHave('lists', function ($query) use ($listIds) {
            $query->whereIn('id', $listIds);
            $this->applyPublicOnlyConstraint($query);
        });
    }

    public function filterByTags(Builder $query, array $tagIds, string $mode = 'any'): Builder
    {
        if ($mode === 'all') {
            foreach ($tagIds as $tagId) {
                $query->whereHas('tags', function ($query) use ($tagId) {
                    $query->where('id', $tagId);

                    if ($this->publicOnly) {
                        $query->publicOnly();
                    }
                });
            }

            return $query;
        }

        return $query->whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('id', $tagIds);
            $this->applyPublicOnlyConstraint($query);
        });
    }

    public function excludeTags(Builder $query, array $tagIds): Builder
    {
        return $query->whereDoesntHave('tags', function ($query) use ($tagIds) {
            $query->whereIn('id', $tagIds);
            $this->applyPublicOnlyConstraint($query);
        });
    }

    public function applyEngineFilters(ScoutBuilder $builder): void
    {
        if ($this->publicOnly) {
            $builder->where('visibility', ModelAttribute::VISIBILITY_PUBLIC);
        }
    }

    private function applyPublicOnlyConstraint(Builder $query): void
    {
        if ($this->publicOnly) {
            $query->publicOnly();
        }
    }
}
