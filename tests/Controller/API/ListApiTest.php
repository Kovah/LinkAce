<?php

namespace Tests\Controller\API;

use App\Models\LinkList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/lists');

        $response->assertUnauthorized();
    }

    public function testIndexRequest(): void
    {
        $list = LinkList::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/lists');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['name' => $list->name],
                ],
            ]);
    }

    public function testMinimalCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/lists', [
            'name' => 'Test List',
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Test List',
            ]);

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testFullCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/lists', [
            'name' => 'Test List',
            'description' => 'There could be a description here',
            'is_private' => false,
            'check_disabled' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Test List',
            ]);

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/lists', [
            'name' => null,
            'description' => ['bla'],
            'is_private' => 'hello',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'description' => 'The description must be a string.',
            'is_private' => 'The is private field must be true or false.',
        ]);
    }

    public function testShowRequest(): void
    {
        $list = LinkList::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/lists/1');

        $expectedLinkApiUrl = 'http://localhost/api/v1/lists/1/links';

        $response->assertOk()
            ->assertJson([
                'name' => $list->name,
                'links' => $expectedLinkApiUrl,
            ]);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJsonAuthorized('api/v1/lists/1');

        $response->assertNotFound();
    }

    public function testUpdateRequest(): void
    {
        LinkList::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/lists/1', [
            'name' => 'Updated List Title',
            'description' => 'Custom Description',
            'is_private' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'name' => 'Updated List Title',
            ]);

        $databaseList = LinkList::first();

        $this->assertEquals('Updated List Title', $databaseList->name);
    }

    public function testInvalidUpdateRequest(): void
    {
        LinkList::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/lists/1', [
            'name' => null,
            'description' => ['bla'],
            'is_private' => 'hello',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'description' => 'The description must be a string.',
            'is_private' => 'The is private field must be true or false.',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJsonAuthorized('api/v1/lists/1', [
            'name' => 'Updated List Title',
            'description' => 'Custom Description',
            'is_private' => false,
        ]);

        $response->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        LinkList::factory()->create();

        $response = $this->deleteJsonAuthorized('api/v1/lists/1');

        $response->assertStatus(200);

        $this->assertEquals(0, LinkList::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJsonAuthorized('api/v1/lists/1');

        $response->assertNotFound();
    }
}
