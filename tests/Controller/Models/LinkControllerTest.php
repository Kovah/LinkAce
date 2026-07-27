<?php

namespace Tests\Controller\Models;

use App\Enums\ModelAttribute;
use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Kovah\HtmlMeta\Facades\HtmlMeta;
use Kovah\HtmlMeta\HtmlMetaResult;
use Tests\Controller\Traits\PreparesTestData;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    private string $basicTestHtml;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->basicTestHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::preventStrayRequests();
        Http::fake([
            'example.com' => Http::response($this->basicTestHtml),
        ]);

        Queue::fake();
    }

    public function test_index_view(): void
    {
        $this->createTestLinks();

        $response = $this->get('links');

        $response->assertOk()
            ->assertSee('https://public-link.com')
            ->assertSee('https://internal-link.com')
            ->assertDontSee('https://private-link.com');

        $this->flushSession();
        $this->get('links?orderBy=created_at&orderDir=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'https://internal-link.com',
                'https://public-link.com',
            ]);

        $this->flushSession();
        $this->get('links?orderBy=created_at&orderDir=wrong-asc')
            ->assertOk()
            ->assertSeeInOrder([
                'https://public-link.com',
                'https://internal-link.com',
            ]);
    }

    public function test_create_view(): void
    {
        $this->get('links/create')->assertOk()->assertSee('Add Link');
    }

    public function test_minimal_store_request(): void
    {
        $this->post('links', [
            'url' => 'https://example.com',
        ])->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('Example Title', $databaseLink->title);
    }

    public function test_full_store_request(): void
    {
        $tag = Tag::factory()->create(['name' => 'testTag']);
        $list = LinkList::factory()->create(['name' => 'Test List']);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => 'My custom title',
            'description' => 'My custom description',
            'lists' => json_encode([$list->id, 'new list']),
            'tags' => json_encode([$tag->id, 'new-tag']),
            'visibility' => 1,
        ])->assertRedirect('links/1');

        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseHas('tags', [
            'id' => 2,
            'name' => 'new-tag',
        ]);
        $this->assertDatabaseCount('lists', 2);
        $this->assertDatabaseHas('lists', [
            'id' => 2,
            'name' => 'new list',
        ]);

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
        $this->assertEquals('My custom title', $databaseLink->title);
        $this->assertEquals('My custom description', $databaseLink->description);
        $this->assertEqualsCanonicalizing(['Test List', 'new list'], $databaseLink->lists->pluck('name')->toArray());
        $this->assertEqualsCanonicalizing(['testTag', 'new-tag'], $databaseLink->tags->pluck('name')->toArray());
    }

    public function test_store_request_with_duplicate(): void
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

    public function test_store_request_with_existing_private_link(): void
    {
        Link::factory()->create(['url' => 'https://example.com', 'user_id' => 2, 'visibility' => 3]);

        $this->post('links', [
            'url' => 'https://example.com',
            'visibility' => 1,
        ])->assertRedirect('links/2');

        $this->assertDatabaseHas('links', [
            'url' => 'https://example.com',
            'user_id' => 2,
            'visibility' => 3,
        ]);

        $this->assertDatabaseHas('links', [
            'url' => 'https://example.com',
            'user_id' => 1,
            'visibility' => 1,
        ]);
    }

    public function test_store_with_connection_exception(): void
    {
        Log::shouldReceive('warning')->once();
        Http::fake([
            'https://bad-example.com' => function () {
                throw new ConnectionException('Unable to reach bad-example.com');
            },
        ]);

        $response = $this->post('links', [
            'url' => 'https://bad-example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/1');

        $databaseLink = Link::first();

        $this->assertFalse($databaseLink->check_disabled);
        $this->assertEquals(Link::STATUS_BROKEN, $databaseLink->status);
        $this->assertEquals('bad-example.com', $databaseLink->title);
    }

    public function test_import_with_malicious_url(): void
    {
        $response = $this->post('links', [
            'url' => 'javascript:alert(1)',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
        ]);

        $response->assertSessionHasErrors(['url' => 'The url format is invalid.']);

        $this->assertDatabaseCount('links', 0);
    }

    public function test_store_request_with_huge_thumbnail(): void
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

    public function test_store_request_with_continue(): void
    {
        $this->post('links', [
            'url' => 'https://example.com',
            'reload_view' => '1',
        ])->assertRedirect('links/create');

        $databaseLink = Link::first();

        $this->assertEquals('https://example.com', $databaseLink->url);
    }

    public function test_store_request_without_archive_backup(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => false,
        ]);

        Http::fake([
            'https://example.com' => Http::response('', 200),
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

    public function test_store_request_without_private_archive_backup(): void
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
            'visibility' => 3,
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function test_store_request_with_foreign_private_tag(): void
    {
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->for($otherUser)->create([
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => json_encode([$tag->id]),
            'visibility' => 1,
        ]);

        // Existing tag should not be added to link
        $this->assertDatabaseCount('link_tags', 0);
    }

    public function test_store_request_for_private_ip(): void
    {
        Log::shouldReceive('warning')->once();
        Http::fake([
            'http://104.102.37.33/research/cold_fusion.html' => Http::response('', 200),
        ]);

        $this->post('links', [
            'url' => 'http://192.168.0.100/admin',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertRedirect('links/1');

        config()->set('html-meta.block_private_ips', false);

        HtmlMeta::shouldReceive('forUrl')
            ->once()
            ->with('http://192.168.0.200/dashboard')
            ->andReturn(new HtmlMetaResult([
                'title' => 'Example Title',
                'description' => 'This an example description',
            ]));

        $this->post('links', [
            'url' => 'http://192.168.0.200/dashboard',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertRedirect('links/2');
    }

    public function test_store_request_does_not_leak_internal_service_response_via_meta(): void
    {
        // Regression test for GHSA-mf8j-5fhh-5cp3 (vector 1): saving a link that points
        // to an internal-only service must not trigger a server-side metadata fetch.
        // Http::preventStrayRequests() (enabled in setUp) makes any un-faked outbound
        // request fail the test, proving no SSRF request is dispatched to the internal host.
        Log::shouldReceive('warning')->once();

        $this->post('links', [
            'url' => 'http://10.0.0.1:9200/',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ])->assertRedirect('links/1');

        $link = Link::first();
        $this->assertEquals('http://10.0.0.1:9200/', $link->url);
        // Title falls back to the host name instead of leaking fetched content from the internal service.
        $this->assertEquals('10.0.0.1', $link->title);
    }

    public function test_validation_error_for_create(): void
    {
        $this->post('links', [
            'url' => null,
        ])->assertSessionHasErrors([
            'url',
        ]);
    }

    public function test_detail_view(): void
    {
        /** @var Link $link */
        [$link, $link2, $link3, $otherUser] = $this->createTestLinks();

        $link->notes()->create([
            'user_id' => $otherUser->id,
            'note' => 'My private Example Note',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->get('links/1')->assertOk()->assertSee('https://public-link.com')->assertDontSee('My private Example Note');
        $this->get('links/2')->assertOk()->assertSee('https://internal-link.com')
            ->assertSee('<strong>Markdown</strong> test', false);
        $this->get('links/3')->assertForbidden();
    }

    public function test_internal_detail_view(): void
    {
        Link::factory()->create(['url' => 'https://public-link.com', 'visibility' => 2]);

        $this->get('links/1')
            ->assertOk()
            ->assertSee('Internal Link')
            ->assertSee('https://public-link.com');
    }

    public function test_private_detail_view(): void
    {
        Link::factory()->create(['url' => 'https://public-link.com', 'visibility' => 3]);

        $this->get('links/1')
            ->assertOk()
            ->assertSee('Private Link')
            ->assertSee('https://public-link.com');
    }

    public function test_edit_view(): void
    {
        $this->createTestLinks();

        $this->get('links/1/edit')->assertOk()->assertSee('https://public-link.com');
        $this->get('links/2/edit')->assertForbidden();
        $this->get('links/3/edit')->assertForbidden();
    }

    public function test_update_response(): void
    {
        $this->createTestLinks();
        $this->createTestLists();
        $this->createTestTags();

        $this->patch('links/1', [
            'url' => 'https://new-public-link.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => json_encode([1, 'new list']),
            'tags' => json_encode([1, 'new-tag']),
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertRedirect('links/1');

        // Check first link update
        $link = Link::first();

        $this->assertEquals('https://new-public-link.com', $link->url);
        $this->assertEquals('New Title', $link->title);
        $this->assertEquals('New Description', $link->description);
        $this->assertEqualsCanonicalizing(['Public List', 'new list'], $link->lists->pluck('name')->toArray());
        $this->assertEqualsCanonicalizing(['Public Tag', 'new-tag'], $link->tags->pluck('name')->toArray());

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
        ])->assertForbidden();

        $this->patch('links/3', [
            'url' => 'https://private-link.com',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertForbidden();

        $this->assertEquals('https://internal-link.com', Link::find(2)->url);
        $this->assertEquals('https://private-link.com', Link::find(3)->url);
    }

    public function test_update_with_malicious_url(): void
    {
        $this->createTestLinks();

        $this->patch('links/1', [
            'url' => 'javascript:alert(1)',
            'title' => 'New Title',
            'description' => 'New Description',
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
            'check_disabled' => '0',
        ])->assertSessionHasErrors([
            'url' => 'The url format is invalid.',
        ]);
    }

    public function test_missing_model_error_for_update(): void
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

    public function test_unique_property_validation(): void
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

    public function test_validation_error_for_update(): void
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

    public function test_delete_response(): void
    {
        $this->createTestLinks();

        $this->delete('links/1')->assertRedirect();

        $databaseLink = Link::withTrashed()->first();
        $this->assertNotNull($databaseLink->deleted_at);

        $this->delete('links/2')->assertForbidden();
        $this->delete('links/3')->assertForbidden();
    }

    public function test_missing_model_error_for_delete(): void
    {
        $this->delete('links/1')->assertNotFound();
    }

    public function test_check_toggle_request(): void
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
        ])->assertForbidden();

        $this->post('links/toggle-check/3', ['toggle' => '1'])->assertForbidden();
    }

    public function test_invalid_check_toggle_request(): void
    {
        Link::factory()->create();

        $this->post('links/toggle-check/1', [
            'toggle' => 'blabla',
        ])->assertSessionHasErrors([
            'toggle',
        ]);
    }

    public function test_mark_working_request(): void
    {
        $this->createTestLinks();
        $link = Link::first();

        $this->post('links/mark-working/1')->assertRedirect('links/1');
        $this->post('links/mark-working/2')->assertForbidden();
        $this->post('links/mark-working/3')->assertForbidden();

        $this->assertEquals(Link::STATUS_OK, $link->refresh()->status);
    }

    public function test_link_display_toggle(): void
    {
        $this->createTestLinks();

        $userSettings = app(UserSettings::class);
        $userSettings->link_display_mode = Link::DISPLAY_LIST_DETAILED;
        $userSettings->save();

        $this->get('links')->assertSee('link-detailed');

        $this->get('links?link-display=1')->assertSee('link-card');
        $userSettings = app(UserSettings::class);
        $this->assertSame($userSettings->link_display_mode, Link::DISPLAY_CARDS);

        $this->get('links?link-display=2')->assertSee('link-simple');
        $userSettings = app(UserSettings::class);
        $this->assertSame($userSettings->link_display_mode, Link::DISPLAY_LIST_SIMPLE);
    }
}
