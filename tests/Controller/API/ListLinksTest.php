<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class ListLinksTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function test_links_request(): void
    {
        $this->createTestLists();
        [$link, $link2, $link3] = $this->createTestLinks();
        $link->lists()->sync([1, 2]);
        $link2->lists()->sync([1, 2]);
        $link3->lists()->sync([1, 2]);

        $this->getJsonAuthorized('api/v2/lists/1/links')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['url' => $link->url])
            ->assertJsonFragment(['url' => $link2->url])
            ->assertJsonMissing(['url' => $link3->url]);

        $this->getJsonAuthorized('api/v2/lists/2/links')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['url' => $link->url])
            ->assertJsonFragment(['url' => $link2->url])
            ->assertJsonMissing(['url' => $link3->url]);

        $this->getJsonAuthorized('api/v2/lists/3/links')
            ->assertForbidden();
    }

    public function test_links_request_without_links(): void
    {
        LinkList::factory()->create();

        $this->getJsonAuthorized('api/v2/lists/1/links')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_show_request_not_found(): void
    {
        $this->getJsonAuthorized('api/v2/lists/1/links')->assertNotFound();
    }

    public function test_cannot_see_private_links_of_other_user_on_public_list(): void
    {
        $victim = User::factory()->create();

        $list = LinkList::factory()->create([
            'user_id' => $victim->id,
            'visibility' => 1,
        ]);

        $publicLink = Link::factory()->create([
            'user_id' => $victim->id,
            'visibility' => 1,
        ]);

        $privateLink = Link::factory()->create([
            'user_id' => $victim->id,
            'visibility' => 3,
        ]);

        $publicLink->lists()->sync([$list->id]);
        $privateLink->lists()->sync([$list->id]);

        $this->getJsonAuthorized("api/v2/lists/{$list->id}/links")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['url' => $publicLink->url])
            ->assertJsonMissing(['url' => $privateLink->url]);
    }
}
