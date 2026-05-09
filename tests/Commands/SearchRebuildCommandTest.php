<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\Fakes\RecordingRebuildEngine;
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
