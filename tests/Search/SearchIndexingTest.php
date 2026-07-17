<?php

namespace Tests\Search;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\LinkRepository;
use App\Repositories\ListRepository;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\Fakes\RecordingIndexingEngine;
use Tests\TestCase;

class SearchIndexingTest extends TestCase
{
    use RefreshDatabase;

    private RecordingIndexingEngine $engine;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->engine = new RecordingIndexingEngine();
        $this->app->instance(EngineManager::class, new class ($this->engine) extends EngineManager {
            public function __construct(private readonly Engine $engine)
            {
            }

            public function engine($name = null): Engine
            {
                return $this->engine;
            }
        });

        Http::fake(['*' => Http::response('<title>Indexed Link</title>')]);

        config(['linkace.search.driver' => 'database']);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_database_mode_does_not_sync_repository_writes_to_external_index(): void
    {
        $link = Link::factory()->create(['user_id' => $this->user->id]);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);
        $list = LinkList::factory()->create(['user_id' => $this->user->id]);
        $this->engine->reset();

        LinkRepository::update($link, [
            'title' => 'Database mode update',
            'tags' => [$tag->id],
            'lists' => [$list->id],
        ]);
        TagRepository::update($tag, ['name' => 'Database Tag']);
        ListRepository::update($list, ['name' => 'Database List']);

        $this->assertSame([], $this->engine->updatedModels);
        $this->assertSame([], $this->engine->deletedModels);
    }

    public function test_link_update_reindexes_once_after_taxonomy_sync_in_external_mode(): void
    {
        $link = Link::factory()->create(['user_id' => $this->user->id]);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);
        $list = LinkList::factory()->create(['user_id' => $this->user->id]);
        $this->engine->reset();

        config(['linkace.search.driver' => 'meilisearch']);

        LinkRepository::update($link, [
            'title' => 'External mode update',
            'tags' => [$tag->id],
            'lists' => [$list->id],
        ]);

        $this->assertSame([Link::class], $this->engine->updatedModels);
        $this->assertSame([$tag->id], $this->engine->updatedPayloads[0]['tag_ids']);
        $this->assertSame([$list->id], $this->engine->updatedPayloads[0]['list_ids']);
    }

    public function test_create_operations_sync_new_models_in_external_mode(): void
    {
        $this->engine->reset();
        config(['linkace.search.driver' => 'meilisearch']);

        LinkRepository::create([
            'url' => 'https://example.com/',
            'title' => null,
            'description' => null,
            'visibility' => 1,
        ]);
        TagRepository::create(['name' => 'External Tag']);
        ListRepository::create(['name' => 'External List']);

        $this->assertSame([
            Link::class,
            Tag::class,
            LinkList::class,
        ], $this->engine->updatedModels);
    }

    public function test_bulk_link_update_reindexes_each_link_once_through_update_path(): void
    {
        $links = Link::factory()->count(2)->create(['user_id' => $this->user->id]);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);
        $list = LinkList::factory()->create(['user_id' => $this->user->id]);
        $this->engine->reset();

        config(['linkace.search.driver' => 'meilisearch']);

        LinkRepository::bulkUpdate($links->pluck('id')->all(), [
            'tags_mode' => 'replace',
            'tags' => [$tag->id],
            'lists_mode' => 'replace',
            'lists' => [$list->id],
            'visibility' => null,
        ]);

        $this->assertSame([Link::class, Link::class], $this->engine->updatedModels);
    }

    public function test_tag_writes_sync_tag_and_reindex_affected_links_on_delete(): void
    {
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);
        $link = Link::factory()->create(['user_id' => $this->user->id]);
        $link->tags()->sync([$tag->id]);
        $this->engine->reset();

        config(['linkace.search.driver' => 'meilisearch']);

        TagRepository::update($tag, ['name' => 'Updated Tag']);
        TagRepository::delete($tag);

        $this->assertSame([Tag::class, Link::class], $this->engine->updatedModels);
        $this->assertSame([Tag::class], $this->engine->deletedModels);
    }

    public function test_list_writes_sync_list_and_reindex_affected_links_on_delete(): void
    {
        $list = LinkList::factory()->create(['user_id' => $this->user->id]);
        $link = Link::factory()->create(['user_id' => $this->user->id]);
        $link->lists()->sync([$list->id]);
        $this->engine->reset();

        config(['linkace.search.driver' => 'meilisearch']);

        ListRepository::update($list, ['name' => 'Updated List']);
        ListRepository::delete($list);

        $this->assertSame([LinkList::class, Link::class], $this->engine->updatedModels);
        $this->assertSame([LinkList::class], $this->engine->deletedModels);
    }
}
