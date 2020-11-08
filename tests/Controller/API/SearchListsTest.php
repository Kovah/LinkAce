<?php

namespace Tests\Controller\API;

use App\Models\LinkList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchListsTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/search/lists');

        $response->assertUnauthorized();
    }

    public function testWithoutQuery(): void
    {
        $response = $this->getJsonAuthorized('api/v1/search/lists');

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function testWithEmptyQuery(): void
    {
        // This list must not be present in the results
        LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $response = $this->getJsonAuthorized('api/v1/search/lists?query=');

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function testWithMultipleResults(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $list2 = LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Articles about Web Development',
        ]);

        // This list must not be present in the results
        LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Learning Theory Resources',
        ]);

        $url = sprintf('api/v1/search/lists?query=%s', 'articles');
        $response = $this->getJsonAuthorized($url);

        $response->assertOk()
            ->assertExactJson([
                $list->id => $list->name,
                $list2->id => $list2->name,
            ]);
    }

    public function testWithShortQuery(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $list2 = LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Articles about Web Development',
        ]);

        // This list must not be present in the results
        LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Quantum Theories',
        ]);

        $url = sprintf('api/v1/search/lists?query=%s', 'ar');
        $response = $this->getJsonAuthorized($url);

        $response->assertOk()
            ->assertExactJson([
                $list->id => $list->name,
                $list2->id => $list2->name,
            ]);
    }
}
