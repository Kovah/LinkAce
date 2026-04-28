<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Contracts\UpdatesIndexSettings;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\TestCase;

class SearchSetupCommandTest extends TestCase
{
    public function test_database_search_requires_no_external_setup(): void
    {
        config(['linkace.search.driver' => 'database']);

        $this->artisan('linkace:search:setup')
            ->expectsOutput('Database search is active. No external search setup is required.')
            ->assertSuccessful();
    }

    public function test_meilisearch_setup_syncs_index_settings(): void
    {
        $engine = new RecordingIndexSettingsEngine();
        $this->app->instance(EngineManager::class, new class ($engine) extends EngineManager {
            public function __construct(private readonly Engine $engine)
            {
            }

            public function engine($name = null): Engine
            {
                return $this->engine;
            }
        });
        config(['linkace.search.driver' => 'meilisearch']);

        $this->artisan('linkace:search:setup')
            ->expectsOutput('Syncing Meilisearch index settings...')
            ->expectsOutput('Meilisearch index settings synced.')
            ->assertSuccessful();

        $this->assertSame([
            'linkace_links',
            'linkace_tags',
            'linkace_lists',
        ], array_keys($engine->updatedSettings));
    }

    public function test_meilisearch_setup_fails_when_engine_cannot_sync_index_settings(): void
    {
        $engine = new UnsupportedIndexSettingsEngine();
        $this->app->instance(EngineManager::class, new class ($engine) extends EngineManager {
            public function __construct(private readonly Engine $engine)
            {
            }

            public function engine($name = null): Engine
            {
                return $this->engine;
            }
        });
        config(['linkace.search.driver' => 'meilisearch']);

        $this->artisan('linkace:search:setup')
            ->expectsOutput('Syncing Meilisearch index settings...')
            ->expectsOutput('The configured Meilisearch engine does not support index settings sync.')
            ->assertFailed();
    }

    public function test_typesense_setup_reports_collection_creation_during_import(): void
    {
        config(['linkace.search.driver' => 'typesense']);

        $this->artisan('linkace:search:setup')
            ->expectsOutput('Typesense collection schemas are configured for '.Link::class.', '.Tag::class.', '.LinkList::class.'.')
            ->expectsOutput('Run linkace:search:rebuild to create or update Typesense collections during import.')
            ->assertSuccessful();
    }
}

class RecordingIndexSettingsEngine extends Engine implements UpdatesIndexSettings
{
    public array $updatedSettings = [];

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

    public function updateIndexSettings(string $name, array $settings = []): void
    {
        $this->updatedSettings[$name] = $settings;
    }

    public function configureSoftDeleteFilter(array $settings = []): array
    {
        return $settings;
    }
}

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
