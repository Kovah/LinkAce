<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLinkOverviewResponse(): void
    {
        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);

        User::factory()->create();

        $publicTag = Tag::factory()->create(['name' => 'publicTag', 'visibility' => 1]);
        $privateTag = Tag::factory()->create(['name' => 'privateTag', 'visibility' => 3]);

        $publicLink = Link::factory()->create(['title' => 'Public Link', 'visibility' => 1]);
        Link::factory()->create(['title' => 'Private Link', 'visibility' => 3]);

        $publicLink->tags()->sync([$publicTag->id, $privateTag->id]);

        $response = $this->get('guest/links');

        $response->assertOk()
            ->assertSee('Public Link')
            ->assertSee('publicTag')
            ->assertDontSee('Private Link')
            ->assertDontSee('privateTag');
    }

    public function testLinkDisplayToggle(): void
    {
        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);

        $this->startSession();
        Link::factory()->create(['title' => 'Public Link', 'visibility' => 1]);

        $this->get('guest/links')->assertSee('link-detailed');

        $this->get('guest/links?link-display=1')->assertSee('link-card');
        $this->assertSame(session('link_display_mode'), Link::DISPLAY_CARDS);

        $this->get('guest/links?link-display=2')->assertSee('link-simple');
        $this->assertSame(session('link_display_mode'), Link::DISPLAY_LIST_SIMPLE);
    }
}
