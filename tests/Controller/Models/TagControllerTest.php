<?php

namespace Tests\Controller\Models;

use App\Models\Tag;
use App\Models\User;
use App\Settings\SettingsAudit;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
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
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags');

        $response->assertOk()->assertSee('Test Tag');
    }

    public function testIndexViewWithValidFilterResult(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags?filter=Test');

        $response->assertOk()
            ->assertSee('Test Tag')
            ->assertDontSee('No Tags found');
    }

    public function testIndexViewWithNoFilterResult(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags?filter=asdfasdfasdf');

        $response->assertOk()
            ->assertSee('No Tags found');
    }

    public function testCreateView(): void
    {
        $response = $this->get('tags/create');

        $response->assertOk()->assertSee('Add Tag');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
        ]);

        $response->assertRedirect('tags/1');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testStoreRequestWithContinue(): void
    {
        $response = $this->post('tags', [
            'name' => 'Test Tag',
            'visibility' => 1,
            'reload_view' => '1',
        ]);

        $response->assertRedirect('tags/create');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('tags', [
            'name' => null,
            'visibility' => 1,
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDetailView(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags/1');

        $response->assertOk()
            ->assertSee($tag->name);
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
        Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags/1/edit');

        $response->assertOk()
            ->assertSee('Edit Tag')
            ->assertSee('Update Tag');
    }

    public function testInvalidEditRequest(): void
    {
        $response = $this->get('tags/1/edit');

        $response->assertNotFound();
    }

    public function testUpdateResponse(): void
    {
        $baseTag = Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->patch('tags/1', [
            'tag_id' => $baseTag->id,
            'name' => 'New Test Tag',
            'visibility' => 1,
        ]);

        $response->assertRedirect('tags/1');

        $updatedLink = $baseTag->fresh();

        $this->assertEquals('New Test Tag', $updatedLink->name);
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->patch('tags/1', [
            'tag_id' => '1',
            'name' => 'New Test Tag',
            'visibility' => 1,
        ]);

        $response->assertNotFound();
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

        $response->assertSessionHasErrors([
            'name',
        ]);
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

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDeleteResponse(): void
    {
        Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->delete('tags/1');

        $response->assertRedirect();

        $databaseTag = Tag::withTrashed()->first();

        $this->assertNotNull($databaseTag->deleted_at);
        $this->assertNotNull($databaseTag->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->delete('tags/1');

        $response->assertNotFound();
    }
}
