<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Controller\Traits\PreparesTrash;

class TrashApiTest extends ApiTestCase
{
    use RefreshDatabase;
    use PreparesTrash;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTrashTestData();
    }

    public function test_unauthorized_request(): void
    {
        $this->getJson('api/v2/trash/links')->assertUnauthorized();
    }

    public function test_get_links(): void
    {
        $response = $this->getJsonAuthorized('api/v2/trash/links');
        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Very special site title', $result[0]->title);
    }

    public function test_get_lists(): void
    {
        $response = $this->getJsonAuthorized('api/v2/trash/lists');
        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('A Tests List', $result[0]->name);
    }

    public function test_get_tags(): void
    {
        $response = $this->getJsonAuthorized('api/v2/trash/tags');
        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Examples', $result[0]->name);
    }

    public function test_get_notes(): void
    {
        $response = $this->getJsonAuthorized('api/v2/trash/notes');
        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Quisque placerat facilisis egestas cillum dolore.', $result[0]->note);
    }

    /*
     * Tests for clearing the trash
     */

    public function test_valid_trash_clear_links_response(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'links',
        ])->assertOk();

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function test_valid_trash_clear_tags_response(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'tags',
        ])->assertOk();

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function test_valid_trash_clear_lists_response(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'lists',
        ])->assertOk();

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function test_valid_trash_clear_notes_response(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'notes',
        ])->assertOk();

        $this->assertEquals(0, DB::table('notes')->count());
    }

    public function test_invalid_trash_clear_request(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            //'model' => 'links',
        ])->assertJsonValidationErrors([
            'model' => 'The model field is required.',
        ]);
    }

    public function test_clear_request_with_invalid_model(): void
    {
        $this->setupTrashTestData();

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'shoes',
        ])->assertJsonValidationErrors([
            'model' => 'The selected model is invalid.',
        ]);
    }

    /*
     * Tests for restoring items
     */

    public function test_valid_restore_link_response(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'link',
            'id' => '1',
        ])->assertOk();

        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function test_valid_restore_tag_response(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ])->assertOk();

        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function test_valid_restore_list_response(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'list',
            'id' => '1',
        ])->assertOk();

        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function test_valid_restore_note_response(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'note',
            'id' => '1',
        ])->assertOk();

        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function test_invalid_restore_response(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            //'model' => 'link',
            //'id' => '1',
        ])->assertJsonValidationErrors([
            'model' => 'The model field is required.',
            'id' => 'The id field is required.',
        ]);
    }

    public function test_restore_with_missing_model(): void
    {
        $this->setupTrashTestData();

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ])->assertNotFound();
    }

    /*
     * Tests for system tokens
     */

    public function test_get_lists_by_system_without_permission(): void
    {
        $this->createTrashedSystemList();
        $this->createSystemToken([ApiToken::ABILITY_LINKS_READ]);

        $this->getJsonAuthorized('api/v2/trash/lists', useSystemToken: true)->assertForbidden();
    }

    public function test_get_lists_by_system_without_private_access(): void
    {
        $this->createTrashedSystemList('Public System List');
        $this->createTrashedSystemList('Private System List', ModelAttribute::VISIBILITY_PRIVATE);
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->getJsonAuthorized('api/v2/trash/lists', useSystemToken: true)
            ->assertOk()
            ->assertJsonFragment(['name' => 'Public System List'])
            ->assertJsonMissing(['name' => 'Private System List']);
    }

    public function test_restore_by_system_without_permission(): void
    {
        $list = $this->createTrashedSystemList();
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'list',
            'id' => $list->id,
        ], useSystemToken: true)->assertForbidden();

        $this->assertTrue($list->fresh()->trashed());
    }

    public function test_restore_by_system_with_permission(): void
    {
        $list = $this->createTrashedSystemList();
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ, ApiToken::ABILITY_LISTS_UPDATE]);

        $this->patchJsonAuthorized('api/v2/trash/restore', [
            'model' => 'list',
            'id' => $list->id,
        ], useSystemToken: true)->assertOk();

        $this->assertFalse($list->fresh()->trashed());
    }

    public function test_clear_by_system_without_permission(): void
    {
        $list = $this->createTrashedSystemList();
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ]);

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'lists',
        ], useSystemToken: true)->assertForbidden();

        $this->assertNotNull(LinkList::withTrashed()->find($list->id));
    }

    public function test_clear_by_system_with_permission(): void
    {
        $list = $this->createTrashedSystemList();
        $this->createSystemToken([ApiToken::ABILITY_LISTS_READ, ApiToken::ABILITY_LISTS_DELETE]);

        $this->deleteJsonAuthorized('api/v2/trash/clear', [
            'model' => 'lists',
        ], useSystemToken: true)->assertOk();

        $this->assertNull(LinkList::withTrashed()->find($list->id));
    }

    protected function createTrashedSystemList(
        string $name = 'System List',
        int $visibility = ModelAttribute::VISIBILITY_PUBLIC
    ): LinkList {
        $list = LinkList::factory()->create([
            'user_id' => User::getSystemUser()->id,
            'name' => $name,
            'visibility' => $visibility,
        ]);
        $list->delete();

        return $list;
    }
}
