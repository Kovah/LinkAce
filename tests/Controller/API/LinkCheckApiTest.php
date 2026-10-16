<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkCheckApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_unauthorized_request(): void
    {
        $this->getJson('api/v2/links/check')
            ->assertUnauthorized();
    }

    public function test_forbidden_link_check_from_system_without_links_read(): void
    {
        Link::factory()->create(['url' => 'https://example.com']);
        $this->createSystemToken();

        $this->getJsonAuthorized('api/v2/links/check?url=https://example.com', useSystemToken: true)
            ->assertForbidden();
    }

    public function test_link_check_from_system_with_links_read(): void
    {
        Link::factory()->create(['url' => 'https://example.com']);
        $this->createSystemToken([ApiToken::ABILITY_LINKS_READ]);

        $this->getJsonAuthorized('api/v2/links/check?url=https://example.com', useSystemToken: true)
            ->assertOk()
            ->assertJson([
                'linksFound' => true,
            ]);
    }

    public function test_successful_link_check(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        $this->getJsonAuthorized('api/v2/links/check?url=https://example.com')
            ->assertOk()
            ->assertJson([
                'linksFound' => true,
            ]);
    }

    public function test_negative_link_check(): void
    {
        Link::factory()->create([
            'url' => 'https://test.com',
        ]);

        $this->getJsonAuthorized('api/v2/links/check?url=https://example.com')
            ->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }

    public function test_check_without_query(): void
    {
        $this->getJsonAuthorized('api/v2/links/check')
            ->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }
}
