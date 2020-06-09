<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTagsTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/search/tags');

        $response->assertUnauthorized();
    }

    public function testWithoutQuery(): void
    {
        $response = $this->getJson('api/v1/search/tags', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    public function testWithEmptyQuery(): void
    {
        // This tag must not be present in the results
        factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'artificial-intelligence',
        ]);

        $response = $this->getJson('api/v1/search/tags?query=', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    public function testWithMultipleResults(): void
    {
        $tag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'programming',
        ]);

        $tag2 = factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'programming-books',
        ]);

        $tag3 = factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'pair-programming',
        ]);

        // This tag must not be present in the results
        factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'learning',
        ]);

        $url = sprintf('api/v1/search/tags?query=%s', 'programming');
        $response = $this->getJson($url, $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([
                $tag->id => $tag->name,
                $tag2->id => $tag2->name,
                $tag3->id => $tag3->name,
            ]);
    }

    public function testWithShortQuery(): void
    {
        $tag = factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'programming',
        ]);

        $tag2 = factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'programming-books',
        ]);

        // This tag must not be present in the results
        factory(Tag::class)->create([
            'user_id' => $this->user->id,
            'name' => 'learning',
        ]);

        $url = sprintf('api/v1/search/tags?query=%s', 'p');
        $response = $this->getJson($url, $this->generateHeaders());

        $response->assertStatus(200)
            ->assertExactJson([
                $tag->id => $tag->name,
                $tag2->id => $tag2->name,
            ]);
    }
}
