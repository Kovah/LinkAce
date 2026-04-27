<?php

namespace App\Search;

use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;

class SearchQuery
{
    public const ORDER_BY_OPTIONS = [
        'title:asc',
        'title:desc',
        'url:asc',
        'url:desc',
        'created_at:asc',
        'created_at:desc',
        'random',
    ];

    public function __construct(
        public readonly ?string $query,
        public readonly bool $searchTitle,
        public readonly bool $searchDescription,
        public readonly ?int $visibility,
        public readonly bool $brokenOnly,
        public readonly array $lists,
        public readonly array $tags,
        public readonly bool $emptyLists,
        public readonly bool $emptyTags,
        public readonly ?string $orderBy,
    ) {
    }

    public static function fromRequest(SearchRequest|Request $request): self
    {
        return new self(
            query: self::nullableString($request->input('query')),
            searchTitle: (bool) $request->input('search_title', false),
            searchDescription: (bool) $request->input('search_description', false),
            visibility: self::nullableInteger($request->input('visibility')),
            brokenOnly: (bool) $request->input('broken_only', false),
            lists: self::parseTaxonomyFilter($request->input('only_lists')),
            tags: self::parseTaxonomyFilter($request->input('only_tags')),
            emptyLists: (bool) $request->input('empty_lists', false),
            emptyTags: (bool) $request->input('empty_tags', false),
            orderBy: self::parseOrderBy($request->input('order_by')),
        );
    }

    public function searchableLinkAttributes(): array
    {
        $attributes = ['url'];

        if ($this->searchTitle) {
            $attributes[] = 'title';
        }

        if ($this->searchDescription) {
            $attributes[] = 'description';
        }

        return $attributes;
    }

    public function hasTextQuery(): bool
    {
        return $this->query !== null;
    }

    public function hasFiltersOnly(): bool
    {
        if ($this->hasTextQuery()) {
            return false;
        }

        return $this->visibility !== null
            || $this->brokenOnly
            || $this->lists !== []
            || $this->tags !== []
            || $this->emptyLists
            || $this->emptyTags;
    }

    private static function nullableString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    private static function nullableInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private static function parseTaxonomyFilter(mixed $value): array
    {
        if ($value === null || $value === '' || $value === []) {
            return [];
        }

        if (is_array($value)) {
            return self::normalizeIds($value);
        }

        $value = (string) $value;
        $decoded = str_starts_with($value, '[') ? json_decode($value, true) : null;

        if (is_array($decoded)) {
            return self::normalizeIds($decoded);
        }

        return self::normalizeIds(explode(',', $value));
    }

    private static function normalizeIds(array $values): array
    {
        return collect($values)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->filter(fn (int $value) => $value > 0)
            ->values()
            ->all();
    }

    private static function parseOrderBy(mixed $value): ?string
    {
        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        $value = (string) $value;

        return in_array($value, self::ORDER_BY_OPTIONS, true) ? $value : self::ORDER_BY_OPTIONS[0];
    }
}
