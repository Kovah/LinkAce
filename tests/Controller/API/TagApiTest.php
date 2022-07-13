<?php

namespace Tests\Controller\API;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class TagApiTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $this->getJson('api/v1/tags')->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $this->createTestTags();

        $this->getJsonAuthorized('api/v1/tags')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['name' => 'Internal Tag'],
                    ['name' => 'Public Tag'],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['name' => 'Private Tag'],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $this->postJsonAuthorized('api/v1/tags', [
            'name' => 'Test Tag',
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testFullCreateRequest(): void
    {
        $this->postJsonAuthorized('api/v1/tags', [
            'name' => 'Test Tag',
            'is_private' => false,
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function testInvalidCreateRequest(): void
    {
        $this->postJsonAuthorized('api/v1/tags', [
            'name' => null,
            'visibility' => 'hello',
        ])->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function testShowRequest(): void
    {
        $this->createTestTags();

        $this->getJsonAuthorized('api/v1/tags/1')
            ->assertOk()
            ->assertJson([
                'name' => 'Public Tag',
            ]);

        $this->getJsonAuthorized('api/v1/tags/2')
            ->assertOk()
            ->assertJson([
                'name' => 'Internal Tag',
            ]);

        $this->getJsonAuthorized('api/v1/tags/3')
            ->assertForbidden();
    }

    public function testShowRequestNotFound(): void
    {
        $this->getJsonAuthorized('api/v1/tags/1')->assertNotFound();
    }

    public function testUpdateRequest(): void
    {
        $this->createTestTags();

        $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => 'Updated Public Tag',
            'visibility' => 1,
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Updated Public Tag',
            ]);

        $databaseList = Tag::find(1);

        $this->assertEquals('Updated Public Tag', $databaseList->name);

        // Test other tags
        $this->patchJsonAuthorized('api/v1/tags/2', [
            'name' => 'Updated Internal Tag',
            'visibility' => 1,
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Updated Internal Tag',
            ]);

        $this->patchJsonAuthorized('api/v1/tags/3', [
            'name' => 'Updated Private Tag',
            'visibility' => 1,
        ])
            ->assertForbidden();
    }

    public function testInvalidUpdateRequest(): void
    {
        Tag::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => null,
            'visibility' => 'hello',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $this->patchJsonAuthorized('api/v1/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ])->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        $this->createTestTags();

        $this->assertEquals(3, Tag::count());

        $this->deleteJsonAuthorized('api/v1/tags/1')->assertOk();
        $this->deleteJsonAuthorized('api/v1/tags/2')->assertOk();
        $this->deleteJsonAuthorized('api/v1/tags/3')->assertForbidden();

        $this->assertEquals(1, Tag::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $this->deleteJsonAuthorized('api/v1/tags/1')->assertNotFound();
    }
}
