<?php

namespace Tests\Controller\Models;

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

class BulkEditControllerTest extends TestCase
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

    public function test_bulk_edit_form(): void
    {
        $this->post('bulk-edit', [
            'type' => 'links',
            'models' => '2,9,42',
        ])->assertOk()->assertSee('You want to edit 3 Links.');
    }

    public function test_links_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $links = $this->prepareLinkTestData();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-links', [
            'models' => '1,2,3,4',
            'tags' => json_encode([3, 'new-tag', '1337']),
            'tags_mode' => 'append',
            'lists' => json_encode([3, 'new list', '1001']),
            'lists_mode' => 'append',
            'visibility' => null,
        ])
            ->assertRedirect('links')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Links out of 4 selected ones.');

        array_walk($links, fn($link) => $link->refresh());

        $this->assertDatabaseCount('tags', 5);
        $this->assertDatabaseHas('tags', [
            'id' => 4,
            'name' => 'new-tag',
        ]);
        $this->assertDatabaseHas('tags', [
            'id' => 5,
            'name' => '1337',
        ]);

        $this->assertDatabaseCount('lists', 5);
        $this->assertDatabaseHas('lists', [
            'id' => 4,
            'name' => 'new list',
        ]);
        $this->assertDatabaseHas('lists', [
            'id' => 5,
            'name' => '1001',
        ]);

        $this->assertEqualsCanonicalizing([3, 1, 4, 5], $links[0]->lists()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5], $links[1]->lists()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([3, 4, 5], $links[2]->lists()->pluck('id')->toArray());

        $this->assertEqualsCanonicalizing([3, 1, 4, 5], $links[0]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5], $links[1]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([3, 4, 5], $links[2]->tags()->pluck('id')->toArray());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $links[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $links[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $links[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherLink->visibility);
    }

    public function test_links_edit_without_taxonomy(): void
    {
        $links = $this->prepareLinkTestData();

        $this->post('bulk-edit/update-links', [
            'models' => '1,2,3,4',
            'tags' => null,
            'tags_mode' => 'append',
            'lists' => null,
            'lists_mode' => 'append',
            'visibility' => null,
        ])
            ->assertRedirect('links')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Links out of 4 selected ones.');

        array_walk($links, fn($link) => $link->refresh());

        $this->assertEqualsCanonicalizing([1], $links[0]->lists()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2], $links[1]->lists()->pluck('id')->toArray());
        $this->assertEmpty($links[2]->lists()->pluck('id')->toArray());

        $this->assertEqualsCanonicalizing([1], $links[0]->tags()->pluck('id')->toArray());
        $this->assertEqualsCanonicalizing([1, 2], $links[1]->tags()->pluck('id')->toArray());
        $this->assertEmpty($links[2]->tags()->pluck('id')->toArray());
    }

    public function test_links_edit_skips_visible_links_owned_by_other_users(): void
    {
        Log::shouldReceive('warning')->times(2);
        $otherUser = User::factory()->create();
        $otherPublicLink = Link::factory()->for($otherUser)->create([
            'url' => 'https://other-public-link.com',
        ]);
        $otherInternalLink = Link::factory()->for($otherUser)->create([
            'url' => 'https://other-internal-link.com',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $this->post('bulk-edit/update-links', [
            'models' => $otherPublicLink->id . ',' . $otherInternalLink->id,
            'tags' => json_encode([]),
            'tags_mode' => 'append',
            'lists' => json_encode([]),
            'lists_mode' => 'append',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ])
            ->assertRedirect('links')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 0 Links out of 2 selected ones.');

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $otherPublicLink->refresh()->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $otherInternalLink->refresh()->visibility);
    }

    public function test_alternative_links_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $links = $this->prepareLinkTestData();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-links', [
            'models' => '1,2,3,4',
            'tags' => json_encode([2, 3]),
            'tags_mode' => 'replace',
            'lists' => json_encode([3]),
            'lists_mode' => 'replace',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ])
            ->assertRedirect('links')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Links out of 4 selected ones.');

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

    public function test_lists_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $lists = $this->createTestLists($this->user);

        $otherUser = User::factory()->create();
        $otherList = LinkList::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-lists', [
            'models' => '1,2,3,4',
            'visibility' => null,
        ])
            ->assertRedirect('lists')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Lists out of 4 selected ones.');

        array_walk($lists, fn($list) => $list->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $lists[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $lists[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherList->visibility);
    }

    public function test_lists_edit_skips_visible_lists_owned_by_other_users(): void
    {
        Log::shouldReceive('warning')->times(2);
        $otherUser = User::factory()->create();
        $otherPublicList = LinkList::factory()->for($otherUser)->create();
        $otherInternalList = LinkList::factory()->for($otherUser)->create([
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $this->post('bulk-edit/update-lists', [
            'models' => $otherPublicList->id . ',' . $otherInternalList->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ])
            ->assertRedirect('lists')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 0 Lists out of 2 selected ones.');

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $otherPublicList->refresh()->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $otherInternalList->refresh()->visibility);
    }

    public function test_alternative_lists_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $lists = $this->createTestLists($this->user);

        $otherUser = User::factory()->create();
        $otherList = LinkList::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-lists', [
            'models' => '1,2,3,4',
            'visibility' => 2,
        ])
            ->assertRedirect('lists')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Lists out of 4 selected ones.');

        array_walk($lists, fn($list) => $list->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $lists[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherList->visibility);
    }

    public function test_tags_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $tags = $this->createTestTags($this->user);

        $otherUser = User::factory()->create();
        $otherTag = Tag::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-tags', [
            'models' => '1,2,3,4',
            'visibility' => null,
        ])
            ->assertRedirect('tags')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Tags out of 4 selected ones.');

        array_walk($tags, fn($tag) => $tag->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $tags[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $tags[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherTag->visibility);
    }

    public function test_tags_edit_skips_visible_tags_owned_by_other_users(): void
    {
        Log::shouldReceive('warning')->times(2);
        $otherUser = User::factory()->create();
        $otherPublicTag = Tag::factory()->for($otherUser)->create();
        $otherInternalTag = Tag::factory()->for($otherUser)->create([
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $this->post('bulk-edit/update-tags', [
            'models' => $otherPublicTag->id . ',' . $otherInternalTag->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ])
            ->assertRedirect('tags')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 0 Tags out of 2 selected ones.');

        $this->assertEquals(ModelAttribute::VISIBILITY_PUBLIC, $otherPublicTag->refresh()->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $otherInternalTag->refresh()->visibility);
    }

    public function test_alternative_tags_edit(): void
    {
        Log::shouldReceive('warning')->once();
        $tags = $this->createTestTags($this->user);

        $otherUser = User::factory()->create();
        $otherTag = Tag::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->post('bulk-edit/update-tags', [
            'models' => '1,2,3,4',
            'visibility' => 2,
        ])
            ->assertRedirect('tags')
            ->assertSessionHas('flash_notification.0.message', 'Successfully updated 3 Tags out of 4 selected ones.');

        array_walk($tags, fn($tag) => $tag->refresh());

        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[0]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[1]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_INTERNAL, $tags[2]->visibility);
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, $otherTag->visibility);
    }

    public function test_deletion()
    {
        Log::shouldReceive('warning')->times(3);
        $otherUser = User::factory()->create();

        $links = $this->createTestLinks($this->user);
        $otherLink = Link::factory()->for($otherUser)->create();
        $lists = $this->createTestLists($this->user);
        $otherList = LinkList::factory()->for($otherUser)->create();
        $tags = $this->createTestTags($this->user);
        $otherTag = Tag::factory()->for($otherUser)->create();

        $this->post('bulk-edit/delete', [
            'models' => '1,2,4',
            'type' => 'links',
        ])
            ->assertRedirect('links')
            ->assertSessionHas(
                'flash_notification.0.message',
                'Successfully moved 2 Links out of 3 selected ones to the trash.'
            );

        array_walk($links, fn($link) => $link->refresh());
        $this->assertNotNull($links[0]->deleted_at);
        $this->assertNotNull($links[1]->deleted_at);
        $this->assertNull($otherLink->deleted_at);

        $this->post('bulk-edit/delete', [
            'models' => '1,2,4',
            'type' => 'lists',
        ])
            ->assertRedirect('lists')
            ->assertSessionHas(
                'flash_notification.1.message',
                'Successfully moved 2 Lists out of 3 selected ones to the trash.'
            );

        array_walk($lists, fn($list) => $list->refresh());
        $this->assertNotNull($lists[0]->deleted_at);
        $this->assertNotNull($lists[1]->deleted_at);
        $this->assertNull($otherList->deleted_at);

        $this->post('bulk-edit/delete', [
            'models' => '1,2,4',
            'type' => 'tags',
        ])
            ->assertRedirect('tags')
            ->assertSessionHas(
                'flash_notification.2.message',
                'Successfully moved 2 Tags out of 3 selected ones to the trash.'
            );

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
