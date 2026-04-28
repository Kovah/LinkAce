<?php

namespace Tests\Search;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchableArrayTest extends TestCase
{
    use RefreshDatabase;

    public function test_link_searchable_metadata_and_array(): void
    {
        $tag = Tag::factory()->create();
        $list = LinkList::factory()->create();
        $link = Link::factory()->create([
            'url' => 'https://example.com/articles/search',
            'title' => 'Searchable Example',
            'description' => 'A useful description',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'status' => Link::STATUS_BROKEN,
        ]);
        $link->tags()->sync([$tag->id]);
        $link->lists()->sync([$list->id]);
        $link->load(['tags', 'lists']);

        $this->assertSame('linkace_links', $link->searchableAs());
        $this->assertFalse($link->shouldBeSearchable());

        config(['linkace.search.driver' => 'meilisearch']);
        $this->assertTrue($link->shouldBeSearchable());

        $this->assertSame([
            'id' => (string) $link->id,
            'user_id' => $link->user_id,
            'url' => 'https://example.com/articles/search',
            'title' => 'Searchable Example',
            'description' => 'A useful description',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'status' => Link::STATUS_BROKEN,
            'tag_ids' => [$tag->id],
            'list_ids' => [$list->id],
            'tags_count' => 1,
            'lists_count' => 1,
            'created_at' => $link->created_at->timestamp,
            'updated_at' => $link->updated_at->timestamp,
        ], $link->toSearchableArray());
    }

    public function test_tag_searchable_metadata_and_array(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Programming',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->assertSame('linkace_tags', $tag->searchableAs());
        $this->assertFalse($tag->shouldBeSearchable());

        config(['linkace.search.driver' => 'typesense']);
        $this->assertTrue($tag->shouldBeSearchable());

        $this->assertSame([
            'id' => (string) $tag->id,
            'user_id' => $tag->user_id,
            'name' => 'Programming',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'created_at' => $tag->created_at->timestamp,
            'updated_at' => $tag->updated_at->timestamp,
        ], $tag->toSearchableArray());
    }

    public function test_list_searchable_metadata_and_array(): void
    {
        $list = LinkList::factory()->create([
            'name' => 'Research Articles',
            'description' => 'Long-form reading material',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);

        $this->assertSame('linkace_lists', $list->searchableAs());
        $this->assertFalse($list->shouldBeSearchable());

        config(['linkace.search.driver' => 'meilisearch']);
        $this->assertTrue($list->shouldBeSearchable());

        $this->assertSame([
            'id' => (string) $list->id,
            'user_id' => $list->user_id,
            'name' => 'Research Articles',
            'description' => 'Long-form reading material',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'created_at' => $list->created_at->timestamp,
            'updated_at' => $list->updated_at->timestamp,
        ], $list->toSearchableArray());
    }

    public function test_link_searchable_rebuild_query_eager_loads_taxonomies(): void
    {
        $query = Link::makeAllSearchableQuery();

        $this->assertArrayHasKey('tags', $query->getEagerLoads());
        $this->assertArrayHasKey('lists', $query->getEagerLoads());
    }
}
