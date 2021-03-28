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

    public function testTagsFeed(): void
    {
        $tagPublic = Tag::factory()->create(['is_private' => false]);
        $tagPrivate = Tag::factory()->create(['is_private' => true]);

        $response = $this->get('guest/tags/feed');

        $response->assertOk()
            ->assertSee($tagPublic->name)
            ->assertDontSee($tagPrivate->name);
    }
}
