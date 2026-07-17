<?php

namespace Tests\Fakes;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Engines\Engine;

class UnsupportedIndexSettingsEngine extends Engine
{
    public function update($models): void
    {
    }

    public function delete($models): void
    {
    }

    public function search(ScoutBuilder $builder): array
    {
        return ['hits' => []];
    }

    public function paginate(ScoutBuilder $builder, $perPage, $page): array
    {
        return ['hits' => [], 'total' => 0];
    }

    public function mapIds($results): \Illuminate\Support\Collection
    {
        return collect();
    }

    public function map(ScoutBuilder $builder, $results, $model): EloquentCollection
    {
        return $model->newCollection();
    }

    public function lazyMap(ScoutBuilder $builder, $results, $model): LazyCollection
    {
        return LazyCollection::empty();
    }

    public function getTotalCount($results): int
    {
        return 0;
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
}
