<?php

namespace Tests\Feature\Controller\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkApiTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/links');

        $response->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->getJson('api/v1/links', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['url' => $link->url],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $response = $this->postJson('api/v1/links', [
            'url' => 'https://duckduckgo.com',
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'url' => 'https://duckduckgo.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('https://duckduckgo.com', $databaseLink->url);
    }

    public function testFullCreateRequest(): void
    {
        $list = factory(LinkList::class)->create();
        $tag = factory(Tag::class)->create();

        $response = $this->postJson('api/v1/links', [
            'url' => 'https://duckduckgo.com',
            'title' => 'Search the Web',
            'description' => 'There could be a description here',
            'lists' => [$list->id],
            'tags' => [$tag->id],
            'is_private' => false,
            'check_disabled' => false,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'url' => 'https://duckduckgo.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('https://duckduckgo.com', $databaseLink->url);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJson('api/v1/links', [
            'url' => null,
            'lists' =>'no array',
            'tags' => 123,
            'is_private' => 'hello',
            'check_disabled' => 'bla',
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'url',
                'lists',
                'tags',
                'is_private',
                'check_disabled',
            ]);
    }

    public function testShowRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->getJson('api/v1/links/1', $this->generateHeaders());

        $response->assertStatus(200)
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

        $response = $this->getJson('api/v1/links/1', $this->generateHeaders());

        $response->assertStatus(200)
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
        $response = $this->getJson('api/v1/links/1', $this->generateHeaders());

        $response->assertStatus(404);
    }

    public function testUpdateRequest(): void
    {
        $link = factory(Link::class)->create();
        $list = factory(LinkList::class)->create();

        $response = $this->patchJson('api/v1/links/1', [
            'url' => 'https://duckduckgo.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [$list->id],
            'is_private' => false,
            'check_disabled' => false,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'url' => 'https://duckduckgo.com',
            ]);

        $databaseLink = Link::first();

        $this->assertEquals('https://duckduckgo.com', $databaseLink->url);
    }

    public function testInvalidUpdateRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->patchJson('api/v1/links/1', [
            //
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'url',
            ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJson('api/v1/links/1', [
            'url' => 'https://duckduckgo.com',
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'lists' => [],
            'tags' => [],
            'is_private' => false,
            'check_disabled' => false,
        ], $this->generateHeaders());

        $response->assertStatus(404);
    }

    public function testDeleteRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->deleteJson('api/v1/links/1', [], $this->generateHeaders());

        $response->assertStatus(200);

        $this->assertEquals(0, Link::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJson('api/v1/links/1', [], $this->generateHeaders());

        $response->assertStatus(404);
    }

    protected function generateHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->user->api_token,
        ];
    }
}
