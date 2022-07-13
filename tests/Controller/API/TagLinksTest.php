<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class TagLinksTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function testLinksRequest(): void
    {
        $this->createTestTags();
        [$link, $link2, $link3] = $this->createTestLinks();
        $link->tags()->sync([1, 2]);
        $link2->tags()->sync([1, 2]);
        $link3->tags()->sync([1, 2]);

        $this->getJsonAuthorized('api/v1/tags/1/links')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                    ['url' => $link2->url],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['url' => $link3->url],
                ],
            ]);

        $this->getJsonAuthorized('api/v1/tags/2/links')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                    ['url' => $link2->url],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['url' => $link3->url],
                ],
            ]);

        $this->getJsonAuthorized('api/v1/tags/3/links')
            ->assertForbidden();
    }

    public function testLinksRequestWithoutLinks(): void
    {
        Tag::factory()->create();

        $this->getJsonAuthorized('api/v1/tags/1/links')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $this->getJsonAuthorized('api/v1/tags/1/links')->assertNotFound();
    }
}
