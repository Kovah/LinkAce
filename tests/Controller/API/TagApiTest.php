<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/tags');

        $response->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/tags');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['name' => $tag->name],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/tags', [
            'name' => 'Test Tag',
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testFullCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/tags', [
            'name' => 'Test Tag',
            'is_private' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/tags', [
            'name' => null,
            'is_private' => 'hello',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'is_private' => 'The is private field must be true or false.',
        ]);
    }

    public function testShowRequest(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/tags/1');

        $response->assertOk()
            ->assertJson([
                'name' => $tag->name,
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJsonAuthorized('api/v1/tags/1');

        $response->assertNotFound();
    }

    public function testUpdateRequest(): void
    {
        Tag::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Updated Tag Title',
            ]);

        $databaseList = Tag::first();

        $this->assertEquals('Updated Tag Title', $databaseList->name);
    }

    public function testInvalidUpdateRequest(): void
    {
        Tag::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => null,
            'is_private' => 'hello',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'is_private' => 'The is private field must be true or false.',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ]);

        $response->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        Tag::factory()->create();

        $response = $this->deleteJsonAuthorized('api/v1/tags/1');

        $response->assertOk();

        $this->assertEquals(0, Tag::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJsonAuthorized('api/v1/tags/1');

        $response->assertNotFound();
    }
}
