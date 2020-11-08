<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\Setting;
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

        $linkPublic = Link::factory()->create(['is_private' => false]);
        $linkPrivate = Link::factory()->create(['is_private' => true]);

        $response = $this->get('guest/links');

        $response->assertOk()
            ->assertSee($linkPublic->url)
            ->assertDontSee($linkPrivate->url);
    }
}
