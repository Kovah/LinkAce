<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BulkStoreApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        Queue::fake();
    }

    public function test_store_links(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml),
            'duckduckgo.com' => Http::response($testHtml),
        ]);

        $testList = LinkList::factory()->create(['name' => 'existing List']);
        $testTag = Tag::factory()->create(['name' => 'existingTag']);

        $response = $this->postJson('api/v2/bulk/links', [
            'models' => [
                [
                    'url' => 'https://example.com',
                    'title' => 'The famous Example',
                    'description' => 'There could be a description here',
                    'lists' => [$testList->id, 'new List'],
                    'tags' => [$testTag->id, 'newTag'],
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

        $this->assertEquals('existing List', $response->json()[0]['lists'][0]['name']);
        $this->assertEquals('new List', $response->json()[0]['lists'][1]['name']);

        $this->assertEquals('existingTag', $response->json()[0]['tags'][0]['name']);
        $this->assertEquals('newTag', $response->json()[0]['tags'][1]['name']);

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

        $this->assertDatabaseCount('lists', 2);
        $this->assertDatabaseHas('lists', [
            'id' => 2,
            'name' => 'new List',
        ]);

        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseHas('tags', [
            'id' => 2,
            'name' => 'newTag',
        ]);
    }

    public function test_store_links_rejects_invalid_url_schemes(): void
    {
        $response = $this->postJson('api/v2/bulk/links', [
            'models' => [
                [
                    'url' => 'javascript:alert(1)',
                    'visibility' => 1,
                ],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['models.0.url']);

        $this->assertDatabaseCount('links', 0);
    }

    public function test_store_links_by_system(): void
    {
        Http::fake(['example.com' => Http::response('<!DOCTYPE html><head><title>Example</title></head></html>')]);
        Sanctum::actingAs(User::getSystemUser(), [ApiToken::ABILITY_SYSTEM_ACCESS, ApiToken::ABILITY_LINKS_READ]);

        $this->postJson('api/v2/bulk/links', [
            'models' => [
                [
                    'url' => 'https://example.com',
                    'visibility' => 1,
                ],
            ],
        ])->assertForbidden();

        $this->assertDatabaseCount('links', 0);
    }

    public function test_store_lists_by_system(): void
    {
        Sanctum::actingAs(User::getSystemUser(), [ApiToken::ABILITY_SYSTEM_ACCESS, ApiToken::ABILITY_LISTS_READ]);

        $this->postJson('api/v2/bulk/lists', [
            'models' => [
                [
                    'name' => 'Example List',
                    'visibility' => 1,
                ],
            ],
        ])->assertForbidden();

        $this->assertDatabaseCount('lists', 0);
    }

    public function test_store_tags_by_system(): void
    {
        Sanctum::actingAs(User::getSystemUser(), [ApiToken::ABILITY_SYSTEM_ACCESS, ApiToken::ABILITY_TAGS_READ]);

        $this->postJson('api/v2/bulk/tags', [
            'models' => [
                [
                    'name' => 'tag-a',
                    'visibility' => 1,
                ],
            ],
        ])->assertForbidden();

        $this->assertDatabaseCount('tags', 0);
    }

    public function test_store_lists(): void
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

    public function test_store_tags(): void
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
