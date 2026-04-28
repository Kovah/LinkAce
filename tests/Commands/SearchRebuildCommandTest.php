<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Contracts\UpdatesIndexSettings;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\TestCase;

class SearchRebuildCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_search_requires_no_external_rebuild(): void
    {
        config(['linkace.search.driver' => 'database']);

        $this->artisan('linkace:search:rebuild')
            ->expectsOutput('Database search is active. No external search index rebuild is required.')
            ->assertSuccessful();
    }

    public function test_external_rebuild_flushes_and_imports_searchable_models(): void
    {
        $engine = new RecordingRebuildEngine();
        $this->app->instance(EngineManager::class, new class ($engine) extends EngineManager {
            public function __construct(private readonly Engine $engine)
            {
            }

            public function engine($name = null): Engine
            {
                return $this->engine;
            }
        });

        config(['linkace.search.driver' => 'database']);

        $user = User::factory()->create();
        Link::factory()->create(['user_id' => $user->id]);
        Tag::factory()->create(['user_id' => $user->id]);
        LinkList::factory()->create(['user_id' => $user->id]);

        config(['linkace.search.driver' => 'meilisearch']);

        $this->artisan('linkace:search:rebuild')
            ->expectsOutput('Syncing Meilisearch index settings...')
            ->expectsOutput('Flushing and importing '.Link::class.'...')
            ->expectsOutput('Flushing and importing '.Tag::class.'...')
            ->expectsOutput('Flushing and importing '.LinkList::class.'...')
            ->expectsOutput('External search engines may finish indexing asynchronously after this command exits.')
            ->assertSuccessful();

        $this->assertSame([
            Link::class,
            Tag::class,
            LinkList::class,
        ], $engine->flushedModels);

        $this->assertSame([
            Link::class,
            Tag::class,
            LinkList::class,
        ], $engine->updatedModels);
    }
}

class RecordingRebuildEngine extends Engine implements UpdatesIndexSettings
{
    public array $updatedSettings = [];
    public array $flushedModels = [];
    public array $updatedModels = [];

    public function update($models): void
    {
        $models->each(function ($model): void {
            $this->updatedModels[] = $model::class;
        });
    }

    public function flush($model): void
    {
        $this->flushedModels[] = $model::class;
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

    public function createIndex($name, array $options = []): void
    {
    }

    public function deleteIndex($name): void
    {
    }

    public function updateIndexSettings(string $name, array $settings = []): void
    {
        $this->updatedSettings[$name] = $settings;
    }

    public function configureSoftDeleteFilter(array $settings = []): array
    {
        return $settings;
    }
}
