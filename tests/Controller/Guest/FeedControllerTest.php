<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'key' => 'system_guest_access',
            'value' => '1',
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
        $listPublic = LinkList::factory()->create(['is_private' => false]);
        $listPrivate = LinkList::factory()->create(['is_private' => true]);

        $response = $this->get('guest/lists/feed');

        $response->assertOk()
            ->assertSee($listPublic->name)
            ->assertDontSee($listPrivate->name);
    }

    public function testListLinkFeed(): void
    {
        $link = LinkList::factory()->create(['is_private' => false]);
        $listLink = Link::factory()->create(['is_private' => false]);
        $privateListLink = Link::factory()->create(['is_private' => true]);
        $unrelatedLink = Link::factory()->create();

        $listLink->lists()->sync([$link->id]);

        $response = $this->get('guest/lists/1/feed');

        $response->assertOk()
            ->assertSee($link->name)
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
        $tagPublic = Tag::factory()->create(['is_private' => false]);
        $tagPrivate = Tag::factory()->create(['is_private' => true]);

        $response = $this->get('guest/tags/feed');

        $response->assertOk()
            ->assertSee($tagPublic->name)
            ->assertDontSee($tagPrivate->name);
    }

    public function testTagLinkFeed(): void
    {
        $tag = Tag::factory()->create(['is_private' => false]);
        $tagLink = Link::factory()->create(['is_private' => false]);
        $privateTagLink = Link::factory()->create(['is_private' => true]);
        $unrelatedLink = Link::factory()->create();

        $tagLink->tags()->sync([$tag->id]);

        $response = $this->get('guest/tags/1/feed');

        $response->assertOk()
            ->assertSee($tag->name)
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
