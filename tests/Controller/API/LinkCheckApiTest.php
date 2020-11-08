<?php

namespace Tests\Controller\API;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkCheckApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/links/check');

        $response->assertUnauthorized();
    }

    public function testSuccessfulLinkCheck(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        $response = $this->getJsonAuthorized('api/v1/links/check?url=https://example.com');

        $response->assertOk()
            ->assertJson([
                'linksFound' => true,
            ]);
    }

    public function testNegativeLinkCheck(): void
    {
        Link::factory()->create([
            'url' => 'https://test.com',
        ]);

        $response = $this->getJsonAuthorized('api/v1/links/check?url=https://example.com');

        $response->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }

    public function testCheckWithoutQuery(): void
    {
        $response = $this->getJsonAuthorized('api/v1/links/check');

        $response->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }
}
