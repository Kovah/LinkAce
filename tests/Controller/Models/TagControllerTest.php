<?php

namespace Tests\Controller\Models;

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

    public function testIndexView(): void
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

    public function testIndexViewWithValidFilterResult(): void
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

    public function testIndexViewWithNoFilterResult(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $this->get('tags?filter=asdfasdfasdf')
            ->assertOk()
            ->assertSee('No Tags found');
    }

    public function testCreateView(): void
    {
        $this->get('tags/create')->assertOk()->assertSee('Add Tag');
    }

    public function testMinimalStoreRequest(): void
    {
        $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
        ])->assertRedirect('tags/1');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testStoreRequestWithContinue(): void
    {
        $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
            'reload_view' => '1',
        ])->assertRedirect('tags/create');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('tags', [
            'name' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDetailView(): void
    {
        $this->createTestTags();

        $this->get('tags/1')->assertOk()->assertSee('Public Tag');
        $this->get('tags/2')->assertOk()->assertSee('Internal Tag');
        $this->get('tags/3')->assertForbidden();
    }

    public function testInternalDetailView(): void
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

    public function testPrivateDetailView(): void
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

    public function testEditView(): void
    {
        $this->createTestTags();

        $this->get('tags/1/edit')->assertOk()->assertSee('Public Tag');
        $this->get('tags/2/edit')->assertOk()->assertSee('Internal Tag');
        $this->get('tags/3/edit')->assertForbidden();
    }

    public function testInvalidEditRequest(): void
    {
        $this->get('tags/1/edit')->assertNotFound();
    }

    public function testUpdateResponse(): void
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

    public function testMissingModelErrorForUpdate(): void
    {
        $this->patch('tags/1', [
            'tag_id' => '1',
            'name' => 'New Test Tag',
            'visibility' => 1,
        ])->assertNotFound();
    }

    public function testUniquePropertyValidation(): void
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

    public function testValidationErrorForUpdate(): void
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

    public function testDeleteResponse(): void
    {
        $this->createTestTags();

        $this->assertEquals(3, Tag::count());

        $this->delete('tags/1')->assertRedirect();
        $this->delete('tags/2')->assertForbidden();
        $this->delete('tags/3')->assertForbidden();

        $this->assertEquals(2, Tag::count());
    }

    public function testMissingModelErrorForDelete(): void
    {
        $this->delete('tags/1')->assertNotFound();
    }
}
