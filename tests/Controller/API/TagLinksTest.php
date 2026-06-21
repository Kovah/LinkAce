<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class TagLinksTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function test_links_request(): void
    {
        $this->createTestTags();
        [$link, $link2, $link3] = $this->createTestLinks();
        $link->tags()->sync([1, 2]);
        $link2->tags()->sync([1, 2]);
        $link3->tags()->sync([1, 2]);

        $this->getJsonAuthorized('api/v2/tags/1/links')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['url' => $link->url])
            ->assertJsonFragment(['url' => $link2->url])
            ->assertJsonMissing(['url' => $link3->url]);

        $this->getJsonAuthorized('api/v2/tags/2/links')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['url' => $link->url])
            ->assertJsonFragment(['url' => $link2->url])
            ->assertJsonMissing(['url' => $link3->url]);

        $this->getJsonAuthorized('api/v2/tags/3/links')
            ->assertForbidden();
    }

    public function test_links_request_without_links(): void
    {
        Tag::factory()->create();

        $this->getJsonAuthorized('api/v2/tags/1/links')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_show_request_not_found(): void
    {
        $this->getJsonAuthorized('api/v2/tags/1/links')->assertNotFound();
    }
}
