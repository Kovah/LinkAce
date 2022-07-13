<?php

namespace Tests\Controller\Models;

use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\Controller\Traits\PreparesTestData;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml),
        ]);

        Queue::fake();
    }

    public function testIndexView(): void
    {
        $this->createTestLinks();

        $response = $this->get('links');

        $response->assertOk()
            ->assertSee('https://public-link.com')
            ->assertSee('https://internal-link.com')
            ->assertDontSee('https://private-link.com');
    }

    public function testCreateView(): void
    {
        $this->get('links/create')->assertOk()->assertSee('Add Link');
    }

    public function testMinimalStoreRequest(): void
    {
        $this->post('links', [
            'url' => 'https://example.com',
        ])->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('Example Title', $databaseLink->title);
    }

    public function testFullStoreRequest(): void
    {
        $tag = Tag::factory()->create();
        $list = LinkList::factory()->create();

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => 'My custom title',
            'description' => 'My custom description',
            'lists' => $list->name,
            'tags' => $tag->name,
            'visibility' => 1,
        ])->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('My custom title', $databaseLink->title);
        $this->assertEquals('My custom description', $databaseLink->description);
        $this->assertEquals($list->name, $databaseLink->lists->first()->name);
        $this->assertEquals($tag->name, $databaseLink->tags->first()->name);
    }

    public function testStoreRequestWithDuplicate(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com/',
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertRedirect('links/2');

        $flashMessages = session('flash_notification', collect());
        $flashMessages->contains('message', trans('link.duplicates_found'));
    }

    public function testStoreRequestWithExistingPrivateLink(): void
    {
        Link::factory()->create(['url' => 'https://existing-private-link.com', 'user_id' => 2, 'visibility' => 3]);

        $this->post('links', [
            'url' => 'https://existing-private-link.com',
            'visibility' => 1,
        ])->assertRedirect('links/2');

        $this->assertDatabaseHas('links', [
            'url' => 'https://existing-private-link.com',
            'user_id' => 2,
            'visibility' => 3,
        ]);

        $this->assertDatabaseHas('links', [
            'url' => 'https://existing-private-link.com',
            'user_id' => 1,
            'visibility' => 1,
        ]);
    }

    public function testStoreRequestWithBrokenUrl(): void
    {
        Http::fake([
            'example.com' => Http::response('', 500),
        ]);

        $this->post('links', [
            'url' => 'example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertTrue($databaseLink->check_disabled);
        $this->assertEquals(Link::STATUS_BROKEN, $databaseLink->status);
        $this->assertEquals('example.com', $databaseLink->title);
    }

    public function testStoreRequestWithHugeThumbnail(): void
    {
        $img = 'https://picsum.photos/1000/500';

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta property="og:image" content="' . $img . '">' .
            '</head></html>';

        Http::fake(['huge-thumbnail.com' => Http::response($testHtml)]);

        $this->post('links', [
            'url' => 'https://huge-thumbnail.com',
        ])->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals($img, $databaseLink->thumbnail);
    }

    public function testStoreRequestWithContinue(): void
    {
        $this->post('links', [
            'url' => 'https://example.com',
            'reload_view' => '1',
        ])->assertRedirect('links/create');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
    }

    public function testStoreRequestWithoutArchiveBackup(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => false,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function testStoreRequestWithoutPrivateArchiveBackup(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => true,
            'archive_private_backups_enabled' => false,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function testValidationErrorForCreate(): void
    {
        $this->post('links', [
            'url' => null,
        ])->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testDetailView(): void
    {
        $this->createTestLinks();

        $this->get('links/1')->assertOk()->assertSee('https://public-link.com');
        $this->get('links/2')->assertOk()->assertSee('https://internal-link.com');
        $this->get('links/3')->assertForbidden();
    }

    public function testInternalDetailView(): void
    {
        Link::factory()->create(['url' => 'https://public-link.com', 'visibility' => 2]);

        $this->get('links/1')
            ->assertOk()
            ->assertSee('Internal Link')
            ->assertSee('https://public-link.com');
    }

    public function testPrivateDetailView(): void
    {
        Link::factory()->create(['url' => 'https://public-link.com', 'visibility' => 3]);

        $this->get('links/1')
            ->assertOk()
            ->assertSee('Private Link')
            ->assertSee('https://public-link.com');
    }

    public function testEditView(): void
    {
        $this->createTestLinks();

        $this->get('links/1/edit')->assertOk()->assertSee('https://public-link.com');
        $this->get('links/2/edit')->assertOk()->assertSee('https://internal-link.com');
        $this->get('links/3/edit')->assertForbidden();
    }

    public function testUpdateResponse(): void
    {
        $this->createTestLinks();

        $this->patch('links/1', [
            'url' => 'https://new-public-link.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertRedirect('links/1');

        // Check first link update
        $link = Link::first();

        $this->assertEquals('https://new-public-link.com', $link->url);
        $this->assertEquals('New Title', $link->title);
        $this->assertEquals('New Description', $link->description);

        $historyData = $link->audits()->first()->getModified();

        $this->assertArrayHasKey('url', $historyData);
        $this->assertEquals('https://public-link.com', $historyData['url']['old']);
        $this->assertEquals($link->url, $historyData['url']['new']);

        // Check update for other links
        $this->patch('links/2', [
            'url' => 'https://internal-link.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertRedirect('links/2');

        $this->patch('links/3', [
            'url' => 'https://private-link.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertForbidden();
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $this->patch('links/1', [
            'link_id' => '1',
            'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertNotFound();
    }

    public function testUniquePropertyValidation(): void
    {
        Link::factory()->create(['url' => 'https://old-example.com']);
        $baseLink = Link::factory()->create();

        $this->patch('links/2', [
            'link_id' => $baseLink->id,
            'url' => 'https://old-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseLink = Link::factory()->create();

        $this->patch('links/1', [
            'link_id' => $baseLink->id,
            //'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testDeleteResponse(): void
    {
        $this->createTestLinks();

        $this->delete('links/1')->assertRedirect();

        $databaseLink = Link::withTrashed()->first();
        $this->assertNotNull($databaseLink->deleted_at);

        $this->delete('links/2')->assertRedirect();
        $this->delete('links/3')->assertForbidden();
    }

    public function testMissingModelErrorForDelete(): void
    {
        $this->delete('links/1')->assertNotFound();
    }

    public function testCheckToggleRequest(): void
    {
        $this->createTestLinks();
        $link = Link::first();

        $this->post('links/toggle-check/1', [
            'toggle' => '1',
        ])->assertRedirect('links/1');

        $this->assertEquals(true, $link->refresh()->check_disabled);

        // Check other links
        $this->post('links/toggle-check/2', [
            'toggle' => '1',
        ])->assertRedirect('links/2');

        $this->post('links/toggle-check/3', ['toggle' => '1'])->assertForbidden();
    }

    public function testInvalidCheckToggleRequest(): void
    {
        Link::factory()->create();

        $this->post('links/toggle-check/1', [
            'toggle' => 'blabla',
        ])->assertSessionHasErrors([
            'toggle',
        ]);
    }

    public function testMarkWorkingRequest(): void
    {
        $this->createTestLinks();
        $link = Link::first();

        $this->post('links/mark-working/1')->assertRedirect('links/1');
        $this->post('links/mark-working/2')->assertRedirect('links/2');
        $this->post('links/mark-working/3')->assertForbidden();

        $this->assertEquals(Link::STATUS_OK, $link->refresh()->status);
    }
}
