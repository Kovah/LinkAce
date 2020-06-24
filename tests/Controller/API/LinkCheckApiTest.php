<?php

namespace Tests\Controller\API;

use App\Models\Link;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LinkCheckApiTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/links/check');

        $response->assertUnauthorized();
    }

    public function testSuccessfulLinkCheck(): void
    {
        factory(Link::class)->create([
            'url' => 'https://example.com',
        ]);

        $response = $this->getJson('api/v1/links/check?url=https://example.com', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'linksFound' => true,
            ]);
    }

    public function testNegativeLinkCheck(): void
    {
        factory(Link::class)->create([
            'url' => 'https://test.com',
        ]);

        $response = $this->getJson('api/v1/links/check?url=https://example.com', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'linksFound' => false,
            ]);
    }

    public function testCheckWithoutQuery(): void
    {
        $response = $this->getJson('api/v1/links/check', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'linksFound' => false,
            ]);
    }
}
