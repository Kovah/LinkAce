<?php

namespace Tests\Controller\API;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaginationApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_per_page_zero_returns_all_items(): void
    {
        Link::factory()->count(5)->create(['user_id' => $this->user->id]);

        $this->getJsonAuthorized('api/v2/links?per_page=0')
            ->assertOk()
            ->assertJson(['per_page' => 999999999, 'total' => 5])
            ->assertJsonCount(5, 'data');
    }

    public function test_large_per_page_is_not_capped(): void
    {
        Link::factory()->count(5)->create(['user_id' => $this->user->id]);

        $this->getJsonAuthorized('api/v2/links?per_page=5000')
            ->assertOk()
            ->assertJson(['per_page' => 5000]);
    }

    public function test_non_numeric_per_page_falls_back_to_the_default(): void
    {
        Link::factory()->count(5)->create(['user_id' => $this->user->id]);

        $this->getJsonAuthorized('api/v2/links?per_page=all')
            ->assertOk()
            ->assertJson(['per_page' => 24]);
    }

    public function test_reasonable_per_page_is_honoured(): void
    {
        Link::factory()->count(5)->create(['user_id' => $this->user->id]);

        $this->getJsonAuthorized('api/v2/links?per_page=50')
            ->assertOk()
            ->assertJson(['per_page' => 50]);
    }
}
