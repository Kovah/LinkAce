<?php

namespace Controller\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BulkStoreApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        Queue::fake();
    }

    public function testStoreLinks(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml),
            'duckduckgo.com' => Http::response($testHtml),
        ]);

        $response = $this->postJson('api/v2/bulk/links', [
            'models' => [
                [
                    'url' => 'https://example.com',
                    'title' => 'The famous Example',
                    'description' => 'There could be a description here',
                    'lists' => [],
                    'tags' => [],
                    'visibility' => 1,
                    'check_disabled' => false,
                ],
                [
                    'url' => 'https://duckduckgo.com',
                    'title' => 'Search the Web',
                    'description' => 'There could be a description here',
                    'lists' => [],
                    'tags' => [],
                    'visibility' => 1,
                    'check_disabled' => false,
                ],
            ],
        ]);

        $response->assertSuccessful()->assertJsonIsArray();
        $this->assertEquals('https://example.com', $response->json()[0]['url']);
        $this->assertEquals('https://duckduckgo.com', $response->json()[1]['url']);

        $this->assertDatabaseHas('links', [
            'id' => 1,
            'url' => 'https://example.com',
            'title' => 'The famous Example',
        ]);

        $this->assertDatabaseHas('links', [
            'id' => 2,
            'url' => 'https://duckduckgo.com',
            'title' => 'Search the Web',
        ]);
    }

    public function testStoreLists(): void
    {
        $response = $this->postJson('api/v2/bulk/lists', [
            'models' => [
                [
                    'name' => 'Example List',
                    'description' => 'Example description for List',
                    'visibility' => 1,
                ],
                [
                    'name' => 'The List of Lists',
                    'description' => 'There could be a description here',
                    'visibility' => 1,
                ],
            ],
        ]);

        $response->assertSuccessful()->assertJsonIsArray();
        $this->assertEquals('Example List', $response->json()[0]['name']);
        $this->assertEquals('The List of Lists', $response->json()[1]['name']);

        $this->assertDatabaseHas('lists', [
            'id' => 1,
            'name' => 'Example List',
            'description' => 'Example description for List',
            'visibility' => 1,
        ]);

        $this->assertDatabaseHas('lists', [
            'id' => 2,
            'name' => 'The List of Lists',
            'description' => 'There could be a description here',
            'visibility' => 1,
        ]);
    }

    public function testStoreTags(): void
    {
        $response = $this->postJson('api/v2/bulk/tags', [
            'models' => [
                [
                    'name' => 'tag-a',
                    'visibility' => 1,
                ],
                [
                    'name' => 'tag-b',
                    'visibility' => 1,
                ],
            ],
        ]);

        $response->assertSuccessful()->assertJsonIsArray();
        $this->assertEquals('tag-a', $response->json()[0]['name']);
        $this->assertEquals('tag-b', $response->json()[1]['name']);

        $this->assertDatabaseHas('tags', [
            'id' => 1,
            'name' => 'tag-a',
            'visibility' => 1,
        ]);

        $this->assertDatabaseHas('tags', [
            'id' => 2,
            'name' => 'tag-b',
            'visibility' => 1,
        ]);
    }
}
