<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTagsTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/search/tags');

        $response->assertUnauthorized();
    }

    public function testWithoutQuery(): void
    {
        $response = $this->getJsonAuthorized('api/v1/search/tags');

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function testWithEmptyQuery(): void
    {
        // This tag must not be present in the results
        Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'artificial-intelligence',
        ]);

        $response = $this->getJsonAuthorized('api/v1/search/tags?query=');

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function testWithMultipleResults(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'programming',
        ]);

        $tag2 = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'programming-books',
        ]);

        $tag3 = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'pair-programming',
        ]);

        // This tag must not be present in the results
        Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'learning',
        ]);

        $url = sprintf('api/v1/search/tags?query=%s', 'programming');
        $response = $this->getJsonAuthorized($url);

        $response->assertOk()
            ->assertExactJson([
                $tag->id => $tag->name,
                $tag2->id => $tag2->name,
                $tag3->id => $tag3->name,
            ]);
    }

    public function testWithShortQuery(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'programming',
        ]);

        $tag2 = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'programming-books',
        ]);

        // This tag must not be present in the results
        Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'learning',
        ]);

        $url = sprintf('api/v1/search/tags?query=%s', 'p');
        $response = $this->getJsonAuthorized($url);

        $response->assertOk()
            ->assertExactJson([
                $tag->id => $tag->name,
                $tag2->id => $tag2->name,
            ]);
    }
}
