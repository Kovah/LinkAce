<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagLinksTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testLinksRequest(): void
    {
        $link = Link::factory()->create();
        $tag = Tag::factory()->create();

        $link->tags()->sync([$tag->id]);

        $response = $this->getJsonAuthorized('api/v1/tags/1/links');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                ],
            ]);
    }

    public function testLinksRequestWithoutLinks(): void
    {
        Tag::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/tags/1/links');

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);

        $responseBody = json_decode($response->content());

        $this->assertEmpty($responseBody->data);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJsonAuthorized('api/v1/tags/1/links');

        $response->assertNotFound();
    }
}
