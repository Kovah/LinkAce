<?php

namespace Tests\Controller\API;

use App\Models\LinkList;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchListsTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/search/lists');

        $response->assertUnauthorized();
    }

    public function testWithoutQuery(): void
    {
        $response = $this->getJson('api/v1/search/lists', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    public function testWithEmptyQuery(): void
    {
        // This list must not be present in the results
        factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $response = $this->getJson('api/v1/search/lists?query=', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    public function testWithMultipleResults(): void
    {
        $list = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $list2 = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Articles about Web Development',
        ]);

        // This list must not be present in the results
        factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Learning Theory Resources',
        ]);

        $url = sprintf('api/v1/search/lists?query=%s', 'articles');
        $response = $this->getJson($url, $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([
                $list->id => $list->name,
                $list2->id => $list2->name,
            ]);
    }

    public function testWithShortQuery(): void
    {
        $list = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $list2 = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Articles about Web Development',
        ]);

        // This list must not be present in the results
        factory(LinkList::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Quantum Theories',
        ]);

        $url = sprintf('api/v1/search/lists?query=%s', 'ar');
        $response = $this->getJson($url, $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([
                $list->id => $list->name,
                $list2->id => $list2->name,
            ]);
    }
}
