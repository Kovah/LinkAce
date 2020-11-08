<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListLinksTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testLinksRequest(): void
    {
        $link = Link::factory()->create();
        $list = LinkList::factory()->create();

        $link->lists()->sync([$list->id]);

        $response = $this->getJsonAuthorized('api/v1/lists/1/links');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                ],
            ]);
    }

    public function testLinksRequestWithoutLinks(): void
    {
        LinkList::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/lists/1/links');

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);

        $responseBody = json_decode($response->content());

        $this->assertEmpty($responseBody->data);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJsonAuthorized('api/v1/lists/1/links');

        $response->assertNotFound();
    }
}
