<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class TagApiTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function test_unauthorized_request(): void
    {
        $this->getJson('api/v2/tags')->assertUnauthorized();
    }

    public function test_index_request(): void
    {
        $this->createTestTags();

        $this->getJsonAuthorized('api/v2/tags')
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

    public function test_minimal_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/tags', [
            'name' => 'Test Tag',
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Test Tag',
            ]);

        $databaseTag = Tag::first();

        $this->assertEquals('Test Tag', $databaseTag->name);
    }

    public function test_full_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/tags', [
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

    public function test_invalid_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/tags', [
            'name' => null,
            'visibility' => 'hello',
        ])->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function test_show_request(): void
    {
        $this->createTestTags();

        $this->getJsonAuthorized('api/v2/tags/1')
            ->assertOk()
            ->assertJson([
                'name' => 'Public Tag',
            ]);

        $this->getJsonAuthorized('api/v2/tags/2')
            ->assertOk()
            ->assertJson([
                'name' => 'Internal Tag',
            ]);

        $this->getJsonAuthorized('api/v2/tags/3')
            ->assertForbidden();
    }

    public function test_show_request_not_found(): void
    {
        $this->getJsonAuthorized('api/v2/tags/1')->assertNotFound();
    }

    public function test_update_request(): void
    {
        $this->createTestTags();

        $this->patchJsonAuthorized('api/v2/tags/1', [
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
        $this->patchJsonAuthorized('api/v2/tags/2', [
            'name' => 'Updated Internal Tag',
            'visibility' => 1,
        ])->assertForbidden();

        $this->patchJsonAuthorized('api/v2/tags/3', [
            'name' => 'Updated Private Tag',
            'visibility' => 1,
        ])
            ->assertForbidden();

        $this->assertEquals('Internal Tag', Tag::find(2)->name);
        $this->assertEquals('Private Tag', Tag::find(3)->name);
    }

    public function test_invalid_update_request(): void
    {
        Tag::factory()->create();

        $this->patchJsonAuthorized('api/v2/tags/1', [
            'name' => null,
            'visibility' => 'hello',
        ])->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function test_update_request_not_found(): void
    {
        $this->patchJsonAuthorized('api/v2/tags/1', [
            'name' => 'Updated Tag Title',
            'is_private' => false,
        ])->assertNotFound();
    }

    public function test_delete_request(): void
    {
        $this->createTestTags();

        $this->assertEquals(3, Tag::count());

        $this->deleteJsonAuthorized('api/v2/tags/1')->assertOk();
        $this->deleteJsonAuthorized('api/v2/tags/2')->assertForbidden();
        $this->deleteJsonAuthorized('api/v2/tags/3')->assertForbidden();

        $this->assertEquals(2, Tag::count());
    }

    public function test_delete_request_not_found(): void
    {
        $this->deleteJsonAuthorized('api/v2/tags/1')->assertNotFound();
    }

    public function test_index_request_by_system_without_permission(): void
    {
        $this->createTestTags();
        $this->createSystemToken();

        $this->getJsonAuthorized('api/v2/tags', useSystemToken: true)
            ->assertForbidden();
    }

    public function test_index_request_by_system_with_permission(): void
    {
        $this->createTestTags();
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ]);

        $this->getJsonAuthorized('api/v2/tags', useSystemToken: true)
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

    public function test_update_request_by_system_without_permission(): void
    {
        $this->createTestTags();
        $tag = Tag::first();
        $this->createSystemToken();

        $this->patchJsonAuthorized('api/v2/tags/' . $tag->id, [
            'name' => 'Updated Tag',
        ], useSystemToken: true)->assertForbidden();
    }

    public function test_update_request_by_system_with_permission(): void
    {
        $this->createTestTags();
        $tag = Tag::first();
        $this->createSystemToken([
            ApiToken::ABILITY_TAGS_READ,
            ApiToken::ABILITY_TAGS_UPDATE,
        ]);

        $this->patchJsonAuthorized('api/v2/tags/' . $tag->id, [
            'name' => 'Updated Tag',
        ], useSystemToken: true)
            ->assertOk()
            ->assertJson([
                'name' => 'Updated Tag',
            ]);
    }

    public function test_delete_request_by_system_without_permission(): void
    {
        $this->createTestTags();
        $tag = Tag::first();
        $this->createSystemToken();

        $this->deleteJsonAuthorized('api/v2/tags/' . $tag->id, useSystemToken: true)->assertForbidden();
    }

    public function test_delete_request_by_system_with_permission(): void
    {
        $this->createTestTags();
        $tag = Tag::first();
        $this->createSystemToken([
            ApiToken::ABILITY_TAGS_READ,
            ApiToken::ABILITY_TAGS_DELETE,
        ]);

        $this->deleteJsonAuthorized('api/v2/tags/' . $tag->id, useSystemToken: true)->assertOk();

        $this->assertEquals(2, Tag::count());
    }

    public function test_create_request_by_system(): void
    {
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ, ApiToken::ABILITY_TAGS_UPDATE]);

        $this->postJsonAuthorized('api/v2/tags', [
            'name' => 'Test Tag',
        ], useSystemToken: true)->assertForbidden();

        $this->assertEquals(0, Tag::count());
    }

    public function test_show_private_system_tag_by_system_without_private_access(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ]);

        $this->getJsonAuthorized('api/v2/tags/' . $tag->id, useSystemToken: true)->assertForbidden();
    }

    public function test_update_system_tag_by_system_without_permission(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'name' => 'System Tag',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ]);

        $this->patchJsonAuthorized('api/v2/tags/' . $tag->id, [
            'name' => 'Updated Tag',
        ], useSystemToken: true)->assertForbidden();

        $this->assertEquals('System Tag', $tag->fresh()->name);
    }

    public function test_update_system_tag_by_system_with_permission(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ, ApiToken::ABILITY_TAGS_UPDATE]);

        $this->patchJsonAuthorized('api/v2/tags/' . $tag->id, [
            'name' => 'Updated Tag',
        ], useSystemToken: true)->assertOk();

        $this->assertEquals('Updated Tag', $tag->fresh()->name);
    }

    public function test_delete_system_tag_by_system_without_permission(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_TAGS_READ]);

        $this->deleteJsonAuthorized('api/v2/tags/' . $tag->id, useSystemToken: true)->assertForbidden();

        $this->assertFalse($tag->fresh()->trashed());
    }
}
