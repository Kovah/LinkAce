<?php

namespace Tests\Controller\API;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkCheckApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $this->getJson('api/v1/links/check')
            ->assertUnauthorized();
    }

    public function testSuccessfulLinkCheck(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        $this->getJsonAuthorized('api/v1/links/check?url=https://example.com')
            ->assertOk()
            ->assertJson([
                'linksFound' => true,
            ]);
    }

    public function testNegativeLinkCheck(): void
    {
        Link::factory()->create([
            'url' => 'https://test.com',
        ]);

        $this->getJsonAuthorized('api/v1/links/check?url=https://example.com')
            ->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }

    public function testCheckWithoutQuery(): void
    {
        $this->getJsonAuthorized('api/v1/links/check')
            ->assertOk()
            ->assertJson([
                'linksFound' => false,
            ]);
    }
}
