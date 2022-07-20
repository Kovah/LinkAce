<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\Controller\Traits\PreparesTestData;

class LinkApiTest extends ApiTestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    protected function setUp(): void
    {
        parent::setUp();

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml),
        ]);

        Queue::fake();
    }

    public function testUnauthorizedRequest(): void
    {
        $this->getJson('api/v1/links')->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $this->createTestLinks();

        $this->getJsonAuthorized('api/v1/links')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => 'https://public-link.com'],
                    ['url' => 'https://internal-link.com'],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['url' => 'https://private-link.com'],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $this->postJsonAuthorized('api/v1/links', [
            'url' => 'https://example.com',
        ])
            ->assertOk()
            ->assertJson([
                'url' => 'https://example.com',
                'description' => 'This an example description',
            ]);
    }

    public function testFullCreateRequest(): void
    {
        $list = LinkList::factory()->create(['name' => 'Test List 1']);
        $tag = Tag::factory()->create(['name' => 'a test 1']);
        $tag2 = Tag::factory()->create(['name' => 'tag #2']);

        $this->postJsonAuthorized('api/v1/links', [
            'url' => 'https://example.com',
            'title' => 'Search the Web',
            'description' => 'There could be a description here',
            'lists' => [$list->id],
            'tags' => [$tag->id, $tag2->id],
            'is_private' => false,
            'check_disabled' => false,
        ])
            ->assertOk()
            ->assertJson([
                'url' => 'https://example.com',
                'lists' => [
                    ['name' => 'Test List 1'],
                ],
                'tags' => [
                    ['name' => 'a test 1'],
                    ['name' => 'tag #2'],
                ],
            ]);
    }

    public function testCreateRequestWithTagsAsString(): void
    {
        $this->postJsonAuthorized('api/v1/links', [
            'url' => 'https://example.com',
            'tags' => 'tag 1, tag 2',
        ])
            ->assertOk()
            ->assertJson([
                'url' => 'https://example.com',
                'tags' => [
                    ['name' => 'tag 1'],
                    ['name' => 'tag 2'],
                ],
            ]);

        $databaseLink = Link::first();
        $this->assertEquals('https://example.com', $databaseLink->url);

        $databaseTag = Tag::first();
        $this->assertEquals('tag 1', $databaseTag->name);
    }

    public function testCreateRequestWithTagsAsArray(): void
    {
        $this->postJsonAuthorized('api/v1/links', [
            'url' => 'https://example.com',
            'tags' => ['tag 1', 'tag 2'],
        ])
            ->assertOk()
            ->assertJson([
                'url' => 'https://example.com',
                'tags' => [
                    ['name' => 'tag 1'],
                    ['name' => 'tag 2'],
                ],
            ]);

        $databaseLink = Link::first();
        $this->assertEquals('https://example.com', $databaseLink->url);

        $databaseTag = Tag::first();
        $this->assertEquals('tag 1', $databaseTag->name);
    }

    public function testCreateRequestWithUnicodeTags(): void
    {
        $this->postJsonAuthorized('api/v1/links', [
            'url' => 'https://example.com',
            'tags' => 'Games 👾, Захватывающе, उत्तेजित करनेवाला',
        ])
            ->assertOk()
            ->assertJson([
                'url' => 'https://example.com',
                'tags' => [
                    ['name' => 'Games 👾'],
                    ['name' => 'Захватывающе'],
                    ['name' => 'उत्तेजित करनेवाला'],
                ],
            ]);

        $databaseTag = Tag::find(1);
        $this->assertEquals('Games 👾', $databaseTag->name);

        $databaseTag2 = Tag::find(2);
        $this->assertEquals('Захватывающе', $databaseTag2->name);

        $databaseTag2 = Tag::find(3);
        $this->assertEquals('उत्तेजित करनेवाला', $databaseTag2->name);
    }

    public function testInvalidCreateRequest(): void
    {
        $this->postJsonAuthorized('api/v1/links', [
            'url' => null,
            'lists' => 'no array',
            'tags' => 123,
            'visibility' => 'hello',
            'check_disabled' => 'bla',
        ])->assertJsonValidationErrors([
            'url' => 'The url field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
            'check_disabled' => 'The check disabled field must be true or false.',
        ]);
    }

    public function testShowRequest(): void
    {
        $this->createTestLinks();

        $this->getJsonAuthorized('api/v1/links/1')->assertOk()->assertJson(['url' => 'https://public-link.com']);
        $this->getJsonAuthorized('api/v1/links/2')->assertOk()->assertJson(['url' => 'https://internal-link.com']);
        $this->getJsonAuthorized('api/v1/links/3')->assertForbidden();
    }

    public function testShowRequestWithRelations(): void
    {
        $link = Link::factory()->create();
        $list = LinkList::factory()->create();
        $tag = Tag::factory()->create();

        $link->lists()->sync([$list->id]);
        $link->tags()->sync([$tag->id]);

        $this->getJsonAuthorized('api/v1/links/1')
            ->assertOk()
            ->assertJson([
                'url' => $link->url,
                'lists' => [
                    ['name' => $list->name],
                ],
                'tags' => [
                    ['name' => $tag->name],
                ],
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $this->getJsonAuthorized('api/v1/links/1')->assertNotFound();
    }

    public function testUpdateRequest(): void
    {
        $list = LinkList::factory()->create();
        $this->createTestLinks();

        $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => 'https://new-public-link.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [$list->id],
            'is_private' => false,
            'check_disabled' => false,
        ])->assertOk()->assertJson(['url' => 'https://new-public-link.com']);

        $this->patchJsonAuthorized('api/v1/links/2', [
            'url' => 'https://new-internal-link.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [$list->id],
            'is_private' => false,
            'check_disabled' => false,
        ])->assertOk()->assertJson(['url' => 'https://new-internal-link.com']);

        $this->patchJsonAuthorized('api/v1/links/3', [
            'url' => 'https://new-internal-link.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [$list->id],
            'is_private' => false,
            'check_disabled' => false,
        ])->assertForbidden();
    }

    public function testInvalidUpdateRequest(): void
    {
        Link::factory()->create();

        $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => null,
            'lists' => 'no array',
            'tags' => 123,
            'visibility' => 'hello',
            'check_disabled' => 'bla',
        ])->assertJsonValidationErrors([
            'url' => 'The url field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
            'check_disabled' => 'The check disabled field must be true or false.',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => 'https://new-public-link.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [],
            'tags' => [],
            'is_private' => false,
            'check_disabled' => false,
        ])->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        $this->createTestLinks();

        $this->assertEquals(3, Link::count());

        $this->deleteJsonAuthorized('api/v1/links/1')->assertOk();
        $this->deleteJsonAuthorized('api/v1/links/2')->assertForbidden();
        $this->deleteJsonAuthorized('api/v1/links/3')->assertForbidden();

        $this->assertEquals(2, Link::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $this->deleteJsonAuthorized('api/v1/links/1')->assertNotFound();
    }
}
