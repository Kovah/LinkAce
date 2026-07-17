<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\Fakes\RecordingIndexSettingsEngine;
use Tests\Fakes\UnsupportedIndexSettingsEngine;
use Tests\TestCase;

class SearchSetupCommandTest extends TestCase
{
    public function test_database_search_requires_no_external_setup(): void
    {
        config(['linkace.search.driver' => 'database']);

        $this->artisan('search:setup')
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

        $this->artisan('search:setup')
            ->expectsOutput('Syncing Meilisearch index settings...')
            ->expectsOutput('Meilisearch index settings synced.')
            ->assertSuccessful();

        $this->assertSame([
            'linkace_links',
            'linkace_tags',
            'linkace_lists',
        ], array_keys($engine->updatedSettings));
    }

    public function test_meilisearch_setup_respects_scout_index_prefix(): void
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
        config([
            'linkace.search.driver' => 'meilisearch',
            'scout.prefix' => 'test_prefix_',
        ]);

        $this->artisan('search:setup')->assertSuccessful();

        $this->assertSame([
            'test_prefix_linkace_links',
            'test_prefix_linkace_tags',
            'test_prefix_linkace_lists',
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

        $this->artisan('search:setup')
            ->expectsOutput('Syncing Meilisearch index settings...')
            ->expectsOutput('The configured Meilisearch engine does not support index settings sync.')
            ->assertFailed();
    }

    public function test_typesense_setup_reports_collection_creation_during_import(): void
    {
        config(['linkace.search.driver' => 'typesense']);

        $this->artisan('search:setup')
            ->expectsOutput('Typesense collection schemas are configured for '.Link::class.', '.Tag::class.', '.LinkList::class.'.')
            ->expectsOutput('Run search:rebuild to create or update Typesense collections during import.')
            ->assertSuccessful();
    }
}
