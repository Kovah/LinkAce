<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLinkOverviewResponse(): void
    {
        Setting::create([
            'key' => 'system_guest_access',
            'value' => '1',
        ]);

        User::factory()->create();

        $publicTag = Tag::factory()->create(['is_private' => false]);
        $privateTag = Tag::factory()->create(['is_private' => true]);

        $linkPublic = Link::factory()->create(['is_private' => false]);
        $linkPrivate = Link::factory()->create(['is_private' => true]);

        $linkPublic->tags()->sync([$publicTag->id, $privateTag->id]);

        $response = $this->get('guest/links');

        $response->assertOk()
            ->assertSee($linkPublic->url)
            ->assertSee($publicTag->name)
            ->assertDontSee($linkPrivate->url)
            ->assertDontSee($privateTag->name);
    }
}
