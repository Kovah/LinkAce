<?php

namespace Tests\Controller\Models;

use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml, 200),
        ]);

        Queue::fake();
    }

    public function testIndexView(): void
    {
        $link = Link::factory()->create();

        $response = $this->get('links');

        $response->assertOk()
            ->assertSee($link->url);
    }

    public function testCreateView(): void
    {
        $response = $this->get('links/create');

        $response->assertOk()
            ->assertSee('Add Link');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('links', [
            'url' => 'https://example.com',
        ]);

        $response->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('Example Title', $databaseLink->title);
    }

    public function testFullStoreRequest(): void
    {
        $tag = Tag::factory()->create();
        $list = LinkList::factory()->create();

        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => 'My custom title',
            'description' => 'My custom description',
            'lists' => $list->name,
            'tags' => $tag->name,
            'is_private' => '1',
        ]);

        $response->assertRedirect('links/1');

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

        $response->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertTrue($databaseLink->is_private);
    }

    public function testStoreRequestWithDuplicate(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com/',
        ]);

        $response = $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/2');

        $flashMessages = session('flash_notification', collect());
        $flashMessages->contains('message', trans('link.duplicates_found'));
    }

    public function testStoreRequestWithBrokenUrl(): void
    {
        Http::fake([
            'example.com' => Http::response('', 500),
        ]);

        $response = $this->post('links', [
            'url' => 'example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertTrue($databaseLink->check_disabled);
        $this->assertEquals(Link::STATUS_BROKEN, $databaseLink->status);
        $this->assertEquals('example.com', $databaseLink->title);
    }

    public function testStoreRequestWithHugeThumbnail(): void
    {
        $img = 'https://assets.imgix.net/unsplash/unsplash006.jpg?w=640&h=400&usm=20&fit=crop&blend-mode=normal&blend-alpha=80&blend-x=30&blend-y=20&blend=https%3A%2F%2Fassets.imgix.net%2F~text%3Ftxt-color%3D9fb64d%26txt-font%3DAvenir%2BNext%2BHeavy%26txt-shad%3D20%26txt-size%3D32%26w%3D580%26txt%3Di%2Bthank%2Byou%2Bgod%2Bfor%2Bmost%2Bthis%2Bamazing%2Bday%3Afor%2Bthe%2Bleaping%2Bgreenly%2Bspirits%2Bof%2Btrees%2B-e.e.%2Bcummings';

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta property="og:image" content="' . $img . '">' .
            '</head></html>';

        Http::fake(['huge-thumbnail.com' => Http::response($testHtml)]);

        $response = $this->post('links', [
            'url' => 'https://huge-thumbnail.com',
        ]);

        $response->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals($img, $databaseLink->thumbnail);
    }

    public function testStoreRequestWithContinue(): void
    {
        $response = $this->post('links', [
            'url' => 'https://example.com',
            'reload_view' => '1',
        ]);

        $response->assertRedirect('links/create');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
    }

    public function testStoreRequestWithoutArchiveBackup(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'archive_backups_enabled',
            'value' => '0',
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function testStoreRequestWithoutPrivateArchiveBackup(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'archive_backups_enabled',
            'value' => '1',
        ]);

        Setting::create([
            'user_id' => 1,
            'key' => 'archive_private_backups_enabled',
            'value' => '0',
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '1',
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('links', [
            'url' => null,
        ]);

        $response->assertSessionHasErrors([
            'url',
        ]);
    }

    public function testDetailView(): void
    {
        $link = Link::factory()->create();

        $response = $this->get('links/1');

        $response->assertOk()
            ->assertSee($link->url);
    }

    public function testEditView(): void
    {
        Link::factory()->create();

        $response = $this->get('links/1/edit');

        $response->assertOk()
            ->assertSee('Edit Link');
    }

    public function testUpdateResponse(): void
    {
        $baseLink = Link::factory()->create();

        $response = $this->patch('links/1', [
            'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
            'check_disabled' => '0',
        ]);

        $response->assertRedirect('links/1');

        $updatedLink = $baseLink->fresh();

        $this->assertEquals('https://new-example.com', $updatedLink->url);
        $this->assertEquals('New Title', $updatedLink->title);
        $this->assertEquals('New Description', $updatedLink->description);

        $historyEntry = $updatedLink->revisionHistory()->first();

        $this->assertEquals('url', $historyEntry->fieldName());
        $this->assertEquals($baseLink->url, $historyEntry->oldValue());
        $this->assertEquals($updatedLink->url, $historyEntry->newValue());
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->patch('links/1', [
            'link_id' => '1',
            'url' => 'https://new-example.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertNotFound();
    }

    public function testUniquePropertyValidation(): void
    {
        Link::factory()->create(['url' => 'https://old-example.com']);
        $baseLink = Link::factory()->create();

        $response = $this->patch('links/2', [
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
        $baseLink = Link::factory()->create();

        $response = $this->patch('links/1', [
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
        Link::factory()->create();

        $response = $this->delete('links/1');

        $response->assertRedirect('links');

        $databaseLink = Link::withTrashed()->first();

        $this->assertNotNull($databaseLink->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->delete('links/1');

        $response->assertNotFound();
    }

    public function testCheckToggleRequest(): void
    {
        $link = Link::factory()->create();

        $response = $this->post('links/toggle-check/1', [
            'toggle' => '1',
        ]);

        $response->assertRedirect('links/1');

        $updatedLink = $link->fresh();

        $this->assertEquals(true, $updatedLink->check_disabled);
    }

    public function testInvalidCheckToggleRequest(): void
    {
        Link::factory()->create();

        $response = $this->post('links/toggle-check/1', [
            'toggle' => 'blabla',
        ]);

        $response->assertSessionHasErrors([
            'toggle',
        ]);
    }

    public function testMarkWorkingRequest(): void
    {
        $link = Link::factory()->create();

        $response = $this->post('links/mark-working/1');

        $response->assertRedirect('links/1');

        $updatedLink = $link->fresh();

        $this->assertEquals(Link::STATUS_OK, $updatedLink->status);
    }
}
