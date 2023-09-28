<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class ListLinksTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function testLinksRequest(): void
    {
        $this->createTestLists();
        [$link, $link2, $link3] = $this->createTestLinks();
        $link->lists()->sync([1, 2]);
        $link2->lists()->sync([1, 2]);
        $link3->lists()->sync([1, 2]);

        $this->getJsonAuthorized('api/v1/lists/1/links')
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

        $this->getJsonAuthorized('api/v1/lists/2/links')
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

        $this->getJsonAuthorized('api/v1/lists/3/links')
            ->assertForbidden();
    }

    public function testLinksRequestWithoutLinks(): void
    {
        LinkList::factory()->create();

        $this->getJsonAuthorized('api/v1/lists/1/links')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $this->getJsonAuthorized('api/v1/lists/1/links')->assertNotFound();
    }
}
