<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
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

    public function testUnauthorizedRequest(): void
    {
        $response = $this->getJson('api/v1/trash/links');

        $response->assertUnauthorized();
    }

    public function testGetLinks(): void
    {
        $response = $this->getJsonAuthorized('api/v1/trash/links');

        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Very special site title', $result[0]->title);
    }

    public function testGetLists(): void
    {
        $response = $this->getJsonAuthorized('api/v1/trash/lists');

        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('A Tests List', $result[0]->name);
    }

    public function testGetTags(): void
    {
        $response = $this->getJsonAuthorized('api/v1/trash/tags');

        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Examples', $result[0]->name);
    }

    public function testGetNotes(): void
    {
        $response = $this->getJsonAuthorized('api/v1/trash/notes');

        $response->assertOk();

        $result = json_decode($response->content());
        $this->assertEquals('Quisque placerat facilisis egestas cillum dolore.', $result[0]->note);
    }

    /*
     * Tests for clearing the trash
     */

    public function testValidTrashClearLinksResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            'model' => 'links',
        ]);

        $response->assertOk();

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function testValidTrashClearTagsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            'model' => 'tags',
        ]);

        $response->assertOk();

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function testValidTrashClearListsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            'model' => 'lists',
        ]);

        $response->assertOk();

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function testValidTrashClearNotesResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            'model' => 'notes',
        ]);

        $response->assertOk();

        $this->assertEquals(0, DB::table('notes')->count());
    }

    public function testInvalidTrashClearRequest(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            //'model' => 'links',
        ]);

        $response->assertJsonValidationErrors([
            'model' => 'The model field is required.',
        ]);
    }

    public function testClearRequestWithInvalidModel(): void
    {
        $this->setupTrashTestData();

        $response = $this->deleteJsonAuthorized('api/v1/trash/clear', [
            'model' => 'shoes',
        ]);

        $response->assertJsonValidationErrors([
            'model' => 'The selected model is invalid.',
        ]);
    }

    /*
     * Tests for restoring items
     */

    public function testValidRestoreLinkResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            'model' => 'link',
            'id' => '1',
        ]);

        $response->assertOk();

        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function testValidRestoreTagResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ]);

        $response->assertOk();

        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function testValidRestoreListResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            'model' => 'list',
            'id' => '1',
        ]);

        $response->assertOk();

        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function testValidRestoreNoteResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            'model' => 'note',
            'id' => '1',
        ]);

        $response->assertOk();

        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function testInvalidRestoreResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            //'model' => 'link',
            //'id' => '1',
        ]);

        $response->assertJsonValidationErrors([
            'model' => 'The model field is required.',
            'id' => 'The id field is required.',
        ]);
    }

    public function testRestoreWithMissingModel(): void
    {
        $this->setupTrashTestData();

        $response = $this->patchJsonAuthorized('api/v1/trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ]);

        $response->assertNotFound();
    }
}
