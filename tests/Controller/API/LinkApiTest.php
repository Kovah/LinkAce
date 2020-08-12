<?php

namespace Tests\Controller\API;

use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class LinkApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/links');

        $response->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->getJsonAuthorized('api/v1/links');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => 'http://example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('http://example.com', $databaseLink->url);
    }

    public function testFullCreateRequest(): void
    {
        $list = factory(LinkList::class)->create();
        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();

        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => 'http://example.com',
            'title' => 'Search the Web',
            'description' => 'There could be a description here',
            'lists' => [$list->id],
            'tags' => [$tag->id, $tag2->id],
            'is_private' => false,
            'check_disabled' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('http://example.com', $databaseLink->url);

        Queue::assertPushed(SaveLinkToWaybackmachine::class);
    }

    public function testCreateRequestWithTagsAsString(): void
    {
        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => 'http://example.com',
            'tags' => 'tag 1, tag 2',
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
            ]);

        $databaseLink = Link::first();
        $this->assertEquals('http://example.com', $databaseLink->url);

        $databaseTag = Tag::first();
        $this->assertEquals('tag 1', $databaseTag->name);
    }

    public function testCreateRequestWithTagsAsArray(): void
    {
        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => 'http://example.com',
            'tags' => ['tag 1', 'tag 2'],
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
            ]);

        $databaseLink = Link::first();
        $this->assertEquals('http://example.com', $databaseLink->url);

        $databaseTag = Tag::first();
        $this->assertEquals('tag 1', $databaseTag->name);
    }

    public function testCreateRequestWithUnicodeTags(): void
    {
        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => 'http://example.com',
            'tags' => 'Games 👾, Захватывающе, उत्तेजित करनेवाला',
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
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
        $response = $this->postJsonAuthorized('api/v1/links', [
            'url' => null,
            'lists' => 'no array',
            'tags' => 123,
            'is_private' => 'hello',
            'check_disabled' => 'bla',
        ]);

        $response->assertJsonValidationErrors([
            'url' => 'The url field is required.',
            'is_private' => 'The is private field must be true or false.',
            'check_disabled' => 'The check disabled field must be true or false.',
        ]);
    }

    public function testShowRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->getJsonAuthorized('api/v1/links/1');

        $response->assertOk()
            ->assertJson([
                'url' => $link->url,
            ]);
    }

    public function testShowRequestWithRelations(): void
    {
        $link = factory(Link::class)->create();
        $list = factory(LinkList::class)->create();
        $tag = factory(Tag::class)->create();

        $link->lists()->sync([$list->id]);
        $link->tags()->sync([$tag->id]);

        $response = $this->getJsonAuthorized('api/v1/links/1');

        $response->assertOk()
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
        $response = $this->getJsonAuthorized('api/v1/links/1');

        $response->assertNotFound();
    }

    public function testUpdateRequest(): void
    {
        factory(Link::class)->create();
        $list = factory(LinkList::class)->create();

        $response = $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => 'http://example.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [$list->id],
            'is_private' => false,
            'check_disabled' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'url' => 'http://example.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('http://example.com', $databaseLink->url);
    }

    public function testInvalidUpdateRequest(): void
    {
        factory(Link::class)->create();

        $response = $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => null,
            'lists' => 'no array',
            'tags' => 123,
            'is_private' => 'hello',
            'check_disabled' => 'bla',
        ]);

        $response->assertJsonValidationErrors([
            'url' => 'The url field is required.',
            'is_private' => 'The is private field must be true or false.',
            'check_disabled' => 'The check disabled field must be true or false.',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJsonAuthorized('api/v1/links/1', [
            'url' => 'http://example.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [],
            'tags' => [],
            'is_private' => false,
            'check_disabled' => false,
        ]);

        $response->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        factory(Link::class)->create();

        $response = $this->deleteJsonAuthorized('api/v1/links/1');

        $response->assertOk();

        $this->assertEquals(0, Link::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJsonAuthorized('api/v1/links/1');

        $response->assertNotFound();
    }
}
