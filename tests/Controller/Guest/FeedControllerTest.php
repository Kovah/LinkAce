<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);
    }

    public function testLinkFeed(): void
    {
        $linkPublic = Link::factory()->create(['is_private' => false]);
        $linkPrivate = Link::factory()->create(['is_private' => true]);

        $response = $this->get('guest/links/feed');

        $response->assertOk()
            ->assertSee($linkPublic->url)
            ->assertDontSee($linkPrivate->url);
    }

    public function testListFeed(): void
    {
        LinkList::factory()->create(['name' => 'public list', 'is_private' => false]);
        LinkList::factory()->create(['name' => 'private list', 'is_private' => true]);

        $response = $this->get('guest/lists/feed');

        $response->assertOk()
            ->assertSee('public list')
            ->assertDontSee('private list');
    }

    public function testListLinkFeed(): void
    {
        $link = LinkList::factory()->create(['name' => 'test link', 'is_private' => false]);
        $listLink = Link::factory()->create(['is_private' => false]);
        $privateListLink = Link::factory()->create(['is_private' => true]);
        $unrelatedLink = Link::factory()->create();

        $listLink->lists()->sync([$link->id]);

        $response = $this->get('guest/lists/1/feed');

        $response->assertOk()
            ->assertSee('test link')
            ->assertSee($listLink->url)
            ->assertDontSee($privateListLink->url)
            ->assertDontSee($unrelatedLink->url);
    }

    public function testPrivateListLinkFeed(): void
    {
        LinkList::factory()->create(['is_private' => true]);

        $response = $this->get('guest/lists/1/feed');

        $response->assertNotFound();
    }

    public function testTagsFeed(): void
    {
        Tag::factory()->create(['name' => 'public tag', 'is_private' => false]);
        Tag::factory()->create(['name' => 'private tag', 'is_private' => true]);

        $response = $this->get('guest/tags/feed');

        $response->assertOk()
            ->assertSee('public tag')
            ->assertDontSee('private tag');
    }

    public function testTagLinkFeed(): void
    {
        $tag = Tag::factory()->create(['name' => 'test tag', 'is_private' => false]);
        $tagLink = Link::factory()->create(['is_private' => false]);
        $privateTagLink = Link::factory()->create(['is_private' => true]);
        $unrelatedLink = Link::factory()->create();

        $tagLink->tags()->sync([$tag->id]);

        $response = $this->get('guest/tags/1/feed');

        $response->assertOk()
            ->assertSee('test tag')
            ->assertSee($tagLink->url)
            ->assertDontSee($privateTagLink->url)
            ->assertDontSee($unrelatedLink->url);
    }

    public function testPrivateTagLinkFeed(): void
    {
        Tag::factory()->create(['is_private' => true]);

        $response = $this->get('guest/tags/1/feed');

        $response->assertNotFound();
    }
}
