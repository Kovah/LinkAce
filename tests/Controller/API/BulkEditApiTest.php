<?php

namespace Tests\Controller\API;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\Controller\Traits\PreparesTestData;
use Tests\TestCase;

class BulkEditApiTest extends TestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        Queue::fake();
    }

    public function testLinksEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $links = $this->prepareLinkTestData();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/links', [
            'models' => [1, 2, 3, 4],
            'tags' => [3],
            'tags_mode' => 'append',
            'lists' => [3],
            'lists_mode' => 'append',
            'visibility' => null,
        ])->assertJsonCount(4);

        array_walk($links, fn($link) => $link->refresh());

        $this->assertEqualsCanonicalizing([1, 3], $links[0]->lists()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2, 3], $links[1]->lists()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([3], $links[2]->lists()->pluck('id')->toArray());

        $this->assertEqualsCanonicalizing([1, 3], $links[0]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2, 3], $links[1]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([3], $links[2]->tags()->pluck('id')->toArray());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $links[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $links[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $links[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherLink->visibility);
    }

    public function testAlternativeLinksEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $links = $this->prepareLinkTestData();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/links', [
            'models' => [1, 2, 3, 4],
            'tags' => [2, 3],
            'tags_mode' => 'replace',
            'lists' => [3],
            'lists_mode' => 'replace',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ])->assertJsonCount(4);

        array_walk($links, fn($link) => $link->refresh());

        $this->assertEqualsCanonicalizing([3], $links[0]->lists()->pluck('id')->sort()->toArray());
        $this->assertEqualsCanonicalizing([3], $links[1]->lists()->pluck('id')->sort()->toArray());
        $this->assertEqualsCanonicalizing([3], $links[2]->lists()->pluck('id')->sort()->toArray());

        $this->assertEqualsCanonicalizing([2, 3], $links[0]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([2, 3], $links[1]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([2, 3], $links[2]->tags()->pluck('id')->toArray());

        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $links[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $links[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $links[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherLink->visibility);
    }

    public function testListsEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $lists = $this->createTestLists($this->user);

        $otherUser = User::factory()->create();
        $otherList = LinkList::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/lists', [
            'models' => [1, 2, 3, 4],
            'visibility' => null,
        ])->assertJsonCount(4);

        array_walk($lists, fn($list) => $list->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $lists[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $lists[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherList->visibility);
    }

    public function testAlternativeListsEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $lists = $this->createTestLists($this->user);

        $otherUser = User::factory()->create();
        $otherList = LinkList::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/lists', [
            'models' => [1, 2, 3, 4],
            'visibility' => 2,
        ])->assertJsonCount(4);

        array_walk($lists, fn($list) => $list->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherList->visibility);
    }

    public function testTagsEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $tags = $this->createTestTags($this->user);

        $otherUser = User::factory()->create();
        $otherTag = Tag::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/tags', [
            'models' => [1, 2, 3, 4],
            'visibility' => null,
        ])->assertJsonCount(4);

        array_walk($tags, fn($tag) => $tag->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $tags[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $tags[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherTag->visibility);
    }

    public function testAlternativeTagsEdit(): void
    {
        Log::shouldReceive('warning')->once();
        $tags = $this->createTestTags($this->user);

        $otherUser = User::factory()->create();
        $otherTag = Tag::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->patch('api/v2/bulk/tags', [
            'models' => [1, 2, 3, 4],
            'visibility' => 2,
        ])->assertJsonCount(4);

        array_walk($tags, fn($tag) => $tag->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherTag->visibility);
    }

    public function testDeletion()
    {
        Log::shouldReceive('warning')->times(3);
        $otherUser = User::factory()->create();

        $links = $this->createTestLinks($this->user);
        $otherLink = Link::factory()->for($otherUser)->create();
        $lists = $this->createTestLists($this->user);
        $otherList = LinkList::factory()->for($otherUser)->create();
        $tags = $this->createTestTags($this->user);
        $otherTag = Tag::factory()->for($otherUser)->create();

        $this->delete('api/v2/bulk/delete', [
            'models' => [1, 2, 4],
            'type' => 'links',
        ])->assertJsonCount(3)->assertJson([
            1 => true,
            2 => true,
            4 => false,
        ]);

        array_walk($links, fn($link) => $link->refresh());
        $this->assertNotNull($links[0]->deleted_at);
        $this->assertNotNull($links[1]->deleted_at);
        $this->assertNull($otherLink->deleted_at);

        $this->delete('api/v2/bulk/delete', [
            'models' => [1, 2, 4],
            'type' => 'lists',
        ])->assertJsonCount(3)->assertJson([
            1 => true,
            2 => true,
            4 => false,
        ]);

        array_walk($lists, fn($list) => $list->refresh());
        $this->assertNotNull($lists[0]->deleted_at);
        $this->assertNotNull($lists[1]->deleted_at);
        $this->assertNull($otherList->deleted_at);

        $this->delete('api/v2/bulk/delete', [
            'models' => [1, 2, 4],
            'type' => 'tags',
        ])->assertJsonCount(3)->assertJson([
            1 => true,
            2 => true,
            4 => false,
        ]);

        array_walk($tags, fn($tag) => $tag->refresh());
        $this->assertNotNull($tags[0]->deleted_at);
        $this->assertNotNull($tags[1]->deleted_at);
        $this->assertNull($otherTag->deleted_at);
    }

    protected function prepareLinkTestData(): array
    {
        $links = $this->createTestLinks($this->user);
        $this->createTestTags($this->user);
        $this->createTestLists($this->user);
        $links[0]->lists()->sync([1]);
        $links[0]->tags()->sync([1]);
        $links[1]->lists()->sync([1, 2]);
        $links[1]->tags()->sync([1, 2]);
        return $links;
    }
}
