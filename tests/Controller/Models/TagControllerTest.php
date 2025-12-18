<?php

namespace Tests\Controller\Models;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_view(): void
    {
        $this->createTestTags();

        $this->get('tags')
            ->assertOk()
            ->assertSee('Public Tag')
            ->assertSee('Internal Tag')
            ->assertDontSee('Private Tag');

        $this->flushSession();
        $this->get('tags?orderBy=created_at&orderDir=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Internal Tag',
                'Public Tag',
            ]);

        $this->flushSession();
        $this->get('tags?orderBy=created_at&orderDir=wrong-desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Public Tag',
                'Internal Tag',
            ]);
    }

    public function test_index_view_with_sorting(): void
    {
        $tag1 = Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);
        $tag2 = Tag::factory()->create([
            'name' => 'Example Tag',
            'user_id' => $this->user->id,
        ]);

        $linksGroups = Link::factory()->count(5)->create()->split(2);
        $linksGroups[0]->each(fn($link) => $link->tags()->attach([$tag1->id]));
        $linksGroups[1]->each(fn($link) => $link->tags()->attach([$tag2->id]));

        $this->get('tags?orderBy=links_count&orderDir=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Test Tag',
                'Example Tag',
            ]);

        $this->get('tags?orderBy=links_count&orderDir=asc')
            ->assertOk()
            ->assertSeeInOrder([
                'Example Tag',
                'Test Tag',
            ]);
    }

    public function test_index_view_with_valid_filter_result(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $this->get('tags?filter=Test')
            ->assertOk()
            ->assertSee('Test Tag')
            ->assertDontSee('No Tags found');
    }

    public function test_index_view_with_no_filter_result(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $this->get('tags?filter=asdfasdfasdf')
            ->assertOk()
            ->assertSee('No Tags found');
    }

    public function test_create_view(): void
    {
        $this->get('tags/create')->assertOk()->assertSee('Add Tag');
    }

    public function test_minimal_store_request(): void
    {
        $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
        ])->assertRedirect('tags/1');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function test_store_request_with_continue(): void
    {
        $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
            'reload_view' => '1',
        ])->assertRedirect('tags/create');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function test_validation_error_for_create(): void
    {
        $this->post('tags', [
            'name' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'name',
        ]);
    }

    public function test_detail_view(): void
    {
        [$tag1, $tag2, $tag3, $otherUser] = $this->createTestTags();

        Link::factory()->for($this->user)->create(['title' => 'FirstTestLink'])->tags()->sync([$tag1->id]);
        Link::factory()->for($this->user)->create([
            'title' => 'SecondTestLink',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ])->tags()->sync([$tag1->id]);

        $this->get('tags/1')->assertOk()->assertSee('Public Tag')
            ->assertSee('FirstTestLink')
            ->assertSee('SecondTestLink');
        $this->get('tags/2')->assertOk()->assertSee('Internal Tag');
        $this->get('tags/3')->assertForbidden();
    }

    public function test_internal_detail_view(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
            'visibility' => 2,
        ]);

        $response = $this->get('tags/1');

        $response->assertOk()
            ->assertSee('Internal Tag')
            ->assertSee($tag->name);
    }

    public function test_private_detail_view(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
            'visibility' => 3,
        ]);

        $response = $this->get('tags/1');

        $response->assertOk()
            ->assertSee('Private Tag')
            ->assertSee($tag->name);
    }

    public function test_edit_view(): void
    {
        $this->createTestTags();

        $this->get('tags/1/edit')->assertOk()->assertSee('Public Tag');
        $this->get('tags/2/edit')->assertOk()->assertSee('Internal Tag');
        $this->get('tags/3/edit')->assertForbidden();
    }

    public function test_invalid_edit_request(): void
    {
        $this->get('tags/1/edit')->assertNotFound();
    }

    public function test_update_response(): void
    {
        $this->createTestTags();

        $this->patch('tags/1', [
            'tag_id' => 1,
            'name' => 'New Public Tag',
            'visibility' => 1,
        ])->assertRedirect('tags/1');

        $updatedTag = Tag::find(1);
        $this->assertEquals('New Public Tag', $updatedTag->name);

        // Test other tags
        $this->patch('tags/2', [
            'tag_id' => 2,
            'name' => 'New Internal Tag',
            'visibility' => 1,
        ])->assertRedirect('tags/2');

        $this->patch('tags/3', [
            'tag_id' => 3,
            'name' => 'New Private Tag',
            'visibility' => 1,
        ])->assertForbidden();
    }

    public function test_missing_model_error_for_update(): void
    {
        $this->patch('tags/1', [
            'tag_id' => '1',
            'name' => 'New Test Tag',
            'visibility' => 1,
        ])->assertNotFound();
    }

    public function test_unique_property_validation(): void
    {
        Tag::factory()->create([
            'name' => 'taken-tag-name',
            'user_id' => $this->user->id,
        ]);

        $baseTag = Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->patch('tags/2', [
            'tag_id' => $baseTag->id,
            'name' => 'taken-tag-name',
            'visibility' => 1,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_validation_error_for_update(): void
    {
        $baseTag = Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->patch('tags/1', [
            'tag_id' => $baseTag->id,
            //'name' => 'New Test Tag',
            'visibility' => 1,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_delete_response(): void
    {
        $this->createTestTags();

        $this->assertEquals(3, Tag::count());

        $this->delete('tags/1')->assertRedirect();
        $this->delete('tags/2')->assertForbidden();
        $this->delete('tags/3')->assertForbidden();

        $this->assertEquals(2, Tag::count());
    }

    public function test_missing_model_error_for_delete(): void
    {
        $this->delete('tags/1')->assertNotFound();
    }
}
