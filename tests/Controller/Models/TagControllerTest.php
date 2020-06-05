<?php

namespace Tests\Controller\Models;

use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user);
    }

    public function testIndexView(): void
    {
        $tag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags');

        $response->assertStatus(200)
            ->assertSee($tag->name);
    }

    public function testCreateView(): void
    {
        $response = $this->get('tags/create');

        $response->assertStatus(200)
            ->assertSee('Add Tag');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('tags', [
            'name' => 'Test Tag',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('tags/1');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testStoreRequestWithPrivateDefault(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'tags_private_default',
            'value' => '1',
        ]);

        $response = $this->post('tags', [
            'name' => 'Test Tag',
            'is_private' => usersettings('tags_private_default'),
        ]);

        $response->assertStatus(302)
            ->assertRedirect('tags/1');

        $databaseList = Tag::first();

        $this->assertTrue($databaseList->is_private);
    }

    public function testStoreRequestWithContinue(): void
    {
        $response = $this->post('tags', [
            'name' => 'Test Tag',
            'is_private' => '1',
            'reload_view' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('tags/create');

        $databaseList = Tag::first();

        $this->assertEquals('Test Tag', $databaseList->name);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('tags', [
            'name' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDetailView(): void
    {
        $tag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags/1');

        $response->assertStatus(200)
            ->assertSee($tag->name);
    }

    public function testEditView(): void
    {
        factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('tags/1/edit');

        $response->assertStatus(200)
            ->assertSee('Edit Tag')
            ->assertSee('Update Tag');
    }

    public function testInvalidEditRequest(): void
    {
        $response = $this->get('tags/1/edit');

        $response->assertStatus(404);
    }

    public function testUpdateResponse(): void
    {
        $baseTag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('tags/1', [
            '_method' => 'patch',
            'tag_id' => $baseTag->id,
            'name' => 'New Test Tag',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('tags/1');

        $updatedLink = $baseTag->fresh();

        $this->assertEquals('New Test Tag', $updatedLink->name);
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->post('tags/1', [
            '_method' => 'patch',
            'tag_id' => '1',
            'name' => 'New Test Tag',
            'is_private' => '0',
        ]);

        $response->assertStatus(404);
    }

    public function testUniquePropertyValidation(): void
    {
        factory(Tag::class)->create([
            'name' => 'taken-tag-name',
            'user_id' => $this->user->id,
        ]);

        $baseTag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('tags/2', [
            '_method' => 'patch',
            'tag_id' => $baseTag->id,
            'name' => 'taken-tag-name',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseTag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('tags/1', [
            '_method' => 'patch',
            'tag_id' => $baseTag->id,
            //'name' => 'New Test Tag',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDeleteResponse(): void
    {
        factory(Tag::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('tags/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('tags');

        $databaseTag = Tag::withTrashed()->first();

        $this->assertNotNull($databaseTag->deleted_at);
        $this->assertNotNull($databaseTag->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->post('tags/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(404);
    }
}
