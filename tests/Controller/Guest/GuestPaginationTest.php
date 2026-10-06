<?php

namespace Tests\Controller\Guest;

use App\Models\Link;
use App\Models\User;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);

        User::factory()->create();
    }

    public function test_per_page_zero_does_not_render_all_links(): void
    {
        Link::factory()->count(30)->create(['visibility' => 1]);

        $response = $this->get('guest/links?per_page=0');

        $response->assertOk();

        // With the guest default of 24 items per page, a 30 link instance must
        // still be paginated instead of rendering everything at once
        $this->assertEquals(24, substr_count($response->content(), 'single-link link-detailed'));
    }

    public function test_excessive_per_page_is_capped_for_guests(): void
    {
        Link::factory()->count(30)->create(['visibility' => 1]);

        config(['linkace.max_pagination' => 10]);

        $response = $this->get('guest/links?per_page=999999999');

        $response->assertOk();

        $this->assertEquals(10, substr_count($response->content(), 'single-link link-detailed'));
    }

    public function test_guest_routes_are_rate_limited(): void
    {
        config(['linkace.guest_rate_limit' => 5]);

        for ($i = 0; $i < 5; $i++) {
            $this->get('guest/links')->assertOk();
        }

        $this->get('guest/links')->assertStatus(429);
    }
}
