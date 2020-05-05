<?php

namespace Tests\Database\Controller\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user);
    }

    public function testIndexView(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->get('links');

        $response->assertStatus(200)
            ->assertSee($link->url);
    }

    public function testCreateView(): void
    {
        $response = $this->get('links/create');

        $response->assertStatus(200)
            ->assertSee('Add Link');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
    }

    public function testFullStoreRequest(): void
    {
        $tag = factory(Tag::class)->create();
        $list = factory(LinkList::class)->create();

        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => 'My custom title',
            'description' => 'My custom description',
            'lists' => $list->name,
            'tags' => $tag->name,
            'is_private' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('My custom title', $databaseLink->title);
        $this->assertEquals('My custom description', $databaseLink->description);
        $this->assertEquals($list->name, $databaseLink->lists->first()->name);
        $this->assertEquals($tag->name, $databaseLink->tags->first()->name);
    }

    public function testStoreRequestWithPrivateDefault(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'links_private_default',
            'value' => '1',
        ]);

        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => usersettings('links_private_default'),
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertTrue($databaseLink->is_private);
        $this->user->load('rawSettings'); // Reload cached settings from other tests
    }

    public function testStoreRequestWithInvalidUrl(): void
    {
        $response = $this->post('links', [
            'url' => 'example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertTrue($databaseLink->check_disabled);
        $this->assertEquals(Link::STATUS_BROKEN, $databaseLink->status);
        $this->assertEquals('example.com', $databaseLink->title);
    }

    public function testStoreRequestWithContinue(): void
    {
        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
            'reload_view' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/create');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('links', [
            'url' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'url',
            'title',
            'description',
            'lists',
            'tags',
        ]);
    }

    public function testDetailView(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->get('links/1');

        $response->assertStatus(200)
            ->assertSee($link->url);
    }

    public function testEditView(): void
    {
        factory(Link::class)->create();

        $response = $this->get('links/1/edit');

        $response->assertStatus(200)
            ->assertSee('Edit Link');
    }

    public function testUpdateResponse(): void
    {
        $baseLink = factory(Link::class)->create();

        $response = $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $baseLink->id,
            'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $updatedLink = $baseLink->fresh();

        $this->assertEquals('https://new-example.com', $updatedLink->url);
        $this->assertEquals('New Title', $updatedLink->title);
        $this->assertEquals('New Description', $updatedLink->description);
    }

    public function testMissingMissingErrorForUpdate(): void
    {
        $response = $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => '1',
            'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertStatus(404);
    }

    public function testUniquePropertyValidation(): void
    {
        factory(Link::class)->create(['url' => 'https://old-example.com']);
        $baseLink = factory(Link::class)->create();

        $response = $this->post('links/2', [
            '_method' => 'patch',
            'link_id' => $baseLink->id,
            'url' => 'https://old-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseLink = factory(Link::class)->create();

        $response = $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $baseLink->id,
            //'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testDeleteResponse(): void
    {
        factory(Link::class)->create();

        $response = $this->post('links/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links');

        $databaseLink = Link::withTrashed()->first();

        $this->assertNotNull($databaseLink->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->post('links/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(404);
    }
}
