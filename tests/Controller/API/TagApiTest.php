<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagApiTest extends TestCase
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
        $response = $this->getJson('api/v1/tags');

        $response->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->getJson('api/v1/tags', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['name' => $tag->name],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $response = $this->postJson('api/v1/tags', [
            'name' => 'Test Tag',
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testFullCreateRequest(): void
    {
        $response = $this->postJson('api/v1/tags', [
            'name' => 'Test Tag',
            'is_private' => false,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJson('api/v1/tags', [
            'name' => null,
            'is_private' => 'hello',
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'is_private',
            ]);
    }

    public function testShowRequest(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->getJson('api/v1/tags/1', $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'name' => $tag->name,
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJson('api/v1/tags/1', $this->generateHeaders());

        $response->assertStatus(404);
    }

    public function testUpdateRequest(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->patchJson('api/v1/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Tag Title',
            ]);

        $databaseList = Tag::first();

        $this->assertEquals('Updated Tag Title', $databaseList->name);
    }

    public function testInvalidUpdateRequest(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->patchJson('api/v1/tags/1', [
            //
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
            ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJson('api/v1/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ], $this->generateHeaders());

        $response->assertStatus(404);
    }

    public function testDeleteRequest(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->deleteJson('api/v1/tags/1', [], $this->generateHeaders());

        $response->assertStatus(200);

        $this->assertEquals(0, Tag::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJson('api/v1/tags/1', [], $this->generateHeaders());

        $response->assertStatus(404);
    }

    protected function generateHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->user->api_token,
        ];
    }
}
