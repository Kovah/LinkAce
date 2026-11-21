<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Models\LinkList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class ListApiTest extends ApiTestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    public function test_unauthorized_request(): void
    {
        $this->getJson('api/v2/lists')->assertUnauthorized();
    }

    public function test_index_request(): void
    {
        $this->createTestLists();

        $this->getJsonAuthorized('api/v2/lists')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['name' => 'Internal List'],
                    ['name' => 'Public List'],
                ],
            ])->assertJsonMissing([
                'data' => [
                    ['name' => 'Private List'],
                ],
            ]);
    }

    public function test_minimal_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/lists', [
            'name' => 'Test List',
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Test List',
            ]);

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function test_full_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/lists', [
            'name' => 'Test List',
            'description' => 'There could be a description here',
            'is_private' => false,
            'check_disabled' => false,
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Test List',
            ]);

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function test_invalid_create_request(): void
    {
        $this->postJsonAuthorized('api/v2/lists', [
            'name' => null,
            'description' => ['bla'],
            'visibility' => 'hello',
        ])->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'description' => 'The description must be a string.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function test_show_request(): void
    {
        $this->createTestLists();

        $expectedLinkApiUrl = 'http://localhost/api/v2/lists/1/links';

        $this->getJsonAuthorized('api/v2/lists/1')
            ->assertOk()
            ->assertJson([
                'name' => 'Public List',
                'links' => $expectedLinkApiUrl,
            ]);

        $this->getJsonAuthorized('api/v2/lists/2')->assertJson(['name' => 'Internal List']);
        $this->getJsonAuthorized('api/v2/lists/3')->assertForbidden();
    }

    public function test_show_request_not_found(): void
    {
        $this->getJsonAuthorized('api/v2/lists/1')->assertNotFound();
    }

    public function test_update_request(): void
    {
        $this->createTestLists();

        $this->patchJsonAuthorized('api/v2/lists/1', [
            'name' => 'Updated Public List',
            'description' => 'Custom Description',
            'visibility' => 1,
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Updated Public List',
            ]);

        $databaseList = LinkList::find(1);
        $this->assertEquals('Updated Public List', $databaseList->name);

        // Test other lists
        $this->patchJsonAuthorized('api/v2/lists/2', [
            'name' => 'Updated Internal List',
            'description' => 'Custom Description',
            'visibility' => 1,
        ])->assertForbidden();

        $this->patchJsonAuthorized('api/v2/lists/3', [
            'name' => 'Updated Internal List',
            'description' => 'Custom Description',
            'visibility' => 1,
        ])->assertForbidden();

        $this->assertEquals('Internal List', LinkList::find(2)->name);
        $this->assertEquals('Private List', LinkList::find(3)->name);
    }

    public function test_invalid_update_request(): void
    {
        LinkList::factory()->create();

        $this->patchJsonAuthorized('api/v2/lists/1', [
            'name' => null,
            'description' => ['bla'],
            'visibility' => 'hello',
        ])->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'description' => 'The description must be a string.',
            'visibility' => 'The Visibility must bei either 1 (public), 2 (internal) or 3 (private).',
        ]);
    }

    public function test_update_request_not_found(): void
    {
        $this->patchJsonAuthorized('api/v2/lists/1', [
            'name' => 'Updated List Title',
            'description' => 'Custom Description',
            'is_private' => false,
        ])->assertNotFound();
    }

    public function test_delete_request(): void
    {
        $this->createTestLists();

        $this->assertEquals(3, LinkList::count());

        $this->deleteJsonAuthorized('api/v2/lists/1')->assertOk();
        $this->deleteJsonAuthorized('api/v2/lists/2')->assertForbidden();
        $this->deleteJsonAuthorized('api/v2/lists/3')->assertForbidden();

        $this->assertEquals(2, LinkList::count());
    }

    public function test_delete_request_not_found(): void
    {
        $this->deleteJsonAuthorized('api/v2/lists/1')->assertNotFound();
    }

    public function test_index_request_by_system_without_permission(): void
    {
        $this->createTestLists();
        $this->createSystemToken();

        $this->getJsonAuthorized('api/v2/lists', useSystemToken: true)
            ->assertForbidden();
    }

    public function test_index_request_by_system_with_permission(): void
    {
        $this->createTestLists();
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->getJsonAuthorized('api/v2/lists', useSystemToken: true)
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['name' => 'Internal List'],
                    ['name' => 'Public List'],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['name' => 'Private List'],
                ],
            ]);
    }

    public function test_update_request_by_system_without_permission(): void
    {
        $this->createTestLists();
        $list = LinkList::first();
        $this->createSystemToken();

        $this->patchJsonAuthorized('api/v2/lists/' . $list->id, [
            'name' => 'Updated List',
        ], useSystemToken: true)->assertForbidden();
    }

    public function test_update_request_by_system_with_permission(): void
    {
        $this->createTestLists();
        $list = LinkList::first();
        $this->createSystemToken([
            ApiToken::ABILITY_LISTS_READ,
            ApiToken::ABILITY_LISTS_UPDATE,
        ]);

        $this->patchJsonAuthorized('api/v2/lists/' . $list->id, [
            'name' => 'Updated List',
        ], useSystemToken: true)
            ->assertOk()
            ->assertJson([
                'name' => 'Updated List',
            ]);
    }

    public function test_delete_request_by_system_without_permission(): void
    {
        $this->createTestLists();
        $list = LinkList::first();
        $this->createSystemToken();

        $this->deleteJsonAuthorized('api/v2/lists/' . $list->id, useSystemToken: true)->assertForbidden();
    }

    public function test_delete_request_by_system_with_permission(): void
    {
        $this->createTestLists();
        $list = LinkList::first();
        $this->createSystemToken([
            ApiToken::ABILITY_LISTS_READ,
            ApiToken::ABILITY_LISTS_DELETE,
        ]);

        $this->deleteJsonAuthorized('api/v2/lists/' . $list->id, useSystemToken: true)->assertOk();

        $this->assertEquals(2, LinkList::count());
    }

    public function test_show_private_system_list_by_system_without_private_access(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->getJsonAuthorized('api/v2/lists/' . $list->id, useSystemToken: true)->assertForbidden();
    }

    public function test_update_system_list_by_system_without_permission(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'name' => 'System List',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->patchJsonAuthorized('api/v2/lists/' . $list->id, [
            'name' => 'Updated List',
        ], useSystemToken: true)->assertForbidden();

        $this->assertEquals('System List', $list->fresh()->name);
    }

    public function test_update_system_list_by_system_with_permission(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ, ApiToken::ABILITY_LISTS_UPDATE]);

        $this->patchJsonAuthorized('api/v2/lists/' . $list->id, [
            'name' => 'Updated List',
        ], useSystemToken: true)->assertOk();

        $this->assertEquals('Updated List', $list->fresh()->name);
    }

    public function test_delete_system_list_by_system_without_permission(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->deleteJsonAuthorized('api/v2/lists/' . $list->id, useSystemToken: true)->assertForbidden();

        $this->assertFalse($list->fresh()->trashed());
    }
}
