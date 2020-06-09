<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagLinksTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testLinksRequest(): void
    {
        $link = factory(Link::class)->create();
        $tag = factory(Tag::class)->create();

        $link->tags()->sync([$tag->id]);

        $response = $this->getJson('api/v1/tags/1/links', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                ],
            ]);
    }

    public function testLinksRequestWithoutLinks(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->getJson('api/v1/tags/1/links', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);

        $responseBody = json_decode($response->content());

        $this->assertEmpty($responseBody->data);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJson('api/v1/tags/1/links', $this->generateHeaders());

        $response->assertStatus(404);
    }
}
