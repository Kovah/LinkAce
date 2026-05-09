<?php

namespace Tests\Fakes;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Engines\Engine;
use RuntimeException;

class RecordingSearchEngine extends Engine
{
    public ?ScoutBuilder $lastPaginatedBuilder = null;
    public ?ScoutBuilder $lastSearchBuilder = null;
    public array $hitIds = [];
    public ?RuntimeException $exception = null;

    public function update($models): void
    {
    }

    public function delete($models): void
    {
    }

    public function search(ScoutBuilder $builder): array
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->lastSearchBuilder = $builder;

        return ['hits' => $this->hits()];
    }

    public function paginate(ScoutBuilder $builder, $perPage, $page): array
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->lastPaginatedBuilder = $builder;

        return [
            'hits' => $this->hits(),
            'total' => count($this->hitIds),
        ];
    }

    public function mapIds($results): \Illuminate\Support\Collection
    {
        return collect($results['hits'])->pluck('id')->values();
    }

    public function map(ScoutBuilder $builder, $results, $model): EloquentCollection
    {
        $ids = collect($results['hits'])->pluck('id')->all();

        if ($ids === []) {
            return $model->newCollection();
        }

        $query = $model->newQuery()->whereIn($model->getKeyName(), $ids);

        if ($builder->queryCallback) {
            ($builder->queryCallback)($query);
        }

        return $query->get()
            ->sortBy(fn ($model) => array_search($model->getKey(), $ids, true))
            ->values();
    }

    public function lazyMap(ScoutBuilder $builder, $results, $model): LazyCollection
    {
        return $this->map($builder, $results, $model)->lazy();
    }

    public function getTotalCount($results): int
    {
        return $results['total'] ?? count($results['hits']);
    }

    public function flush($model): void
    {
    }

    public function createIndex($name, array $options = []): void
    {
    }

    public function deleteIndex($name): void
    {
    }

    private function hits(): array
    {
        return collect($this->hitIds)
            ->map(fn (int $id) => ['id' => $id])
            ->all();
    }
}
