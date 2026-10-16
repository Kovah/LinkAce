<?php

namespace Tests\Helper;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PaginationLimitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /*
     * Authenticated requests: users may request any page size for their own
     * items, including per_page=0 to get all of them at once.
     */

    public function test_default_limit_without_per_page(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/links', []);

        $this->assertEquals(24, getPaginationLimit());
    }

    public function test_per_page_zero_returns_all_items(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/links', ['per_page' => '0']);

        $this->assertEquals(999999999, getPaginationLimit());
    }

    public function test_large_per_page_is_not_capped(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/links', ['per_page' => '5000']);

        $this->assertEquals(5000, getPaginationLimit());
    }

    public function test_non_numeric_per_page_falls_back_to_the_default(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/links', ['per_page' => 'all']);

        $this->assertEquals(24, getPaginationLimit());
    }

    public function test_negative_per_page_falls_back_to_the_default(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/links', ['per_page' => '-5']);

        $this->assertEquals(24, getPaginationLimit());
    }

    /*
     * Guest requests: publicly visible data must never be loaded without a
     * limit, as the request is unauthenticated.
     */

    public function test_guest_per_page_zero_falls_back_to_the_default(): void
    {
        $this->requestWith('/guest/links', ['per_page' => '0']);

        $this->assertEquals(24, getPaginationLimit());
    }

    public function test_guest_non_numeric_per_page_falls_back_to_the_default(): void
    {
        $this->requestWith('/guest/links', ['per_page' => 'all']);

        $this->assertEquals(24, getPaginationLimit());
    }

    public function test_guest_excessive_per_page_is_capped(): void
    {
        $this->requestWith('/guest/links', ['per_page' => '999999999']);

        $this->assertEquals(200, getPaginationLimit());
    }

    public function test_guest_reasonable_per_page_is_honoured(): void
    {
        $this->requestWith('/guest/links', ['per_page' => '50']);

        $this->assertEquals(50, getPaginationLimit());
    }

    public function test_authenticated_user_on_guest_routes_is_capped(): void
    {
        $this->actingAs($this->user);
        $this->requestWith('/guest/links', ['per_page' => '999999999']);

        $this->assertEquals(200, getPaginationLimit());
    }

    private function requestWith(string $uri, array $query): void
    {
        $this->app->instance('request', Request::create($uri, 'GET', $query));
    }
}
