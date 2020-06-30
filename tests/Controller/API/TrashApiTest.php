<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Controller\Traits\PreparesTrash;

class TrashApiTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
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
        $response = $this->getJson('api/v1/trash/links', $this->generateHeaders());

        $response->assertStatus(200);

        $result = json_decode($response->content());
        $this->assertEquals('Very special site title', $result[0]->title);
    }

    public function testGetLists(): void
    {
        $response = $this->getJson('api/v1/trash/lists', $this->generateHeaders());

        $response->assertStatus(200);

        $result = json_decode($response->content());
        $this->assertEquals('A Tests List', $result[0]->name);
    }

    public function testGetTags(): void
    {
        $response = $this->getJson('api/v1/trash/tags', $this->generateHeaders());

        $response->assertStatus(200);

        $result = json_decode($response->content());
        $this->assertEquals('Examples', $result[0]->name);
    }

    public function testGetNotes(): void
    {
        $response = $this->getJson('api/v1/trash/notes', $this->generateHeaders());

        $response->assertStatus(200);

        $result = json_decode($response->content());
        $this->assertEquals('Quisque placerat facilisis egestas cillum dolore.', $result[0]->note);
    }

    /*
     * Tests for clearing the trash
     */

    public function testValidTrashClearLinksResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            'model' => 'links',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function testValidTrashClearTagsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            'model' => 'tags',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function testValidTrashClearListsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            'model' => 'lists',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function testValidTrashClearNotesResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            'model' => 'notes',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(0, DB::table('notes')->count());
    }

    public function testInvalidTrashClearRequest(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            //'model' => 'links',
        ], $this->generateHeaders());

        $response->assertJsonValidationErrors([
            'model' => 'The model field is required.',
        ]);
    }

    public function testClearRequestWithInvalidModel(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/clear', [
            'model' => 'shoes',
        ], $this->generateHeaders());

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

        $response = $this->postJson('api/v1/trash/restore', [
            'model' => 'link',
            'id' => '1',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function testValidRestoreTagResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function testValidRestoreListResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/restore', [
            'model' => 'list',
            'id' => '1',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function testValidRestoreNoteResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/restore', [
            'model' => 'note',
            'id' => '1',
        ], $this->generateHeaders());

        $response->assertOk();

        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function testInvalidRestoreResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/restore', [
            //'model' => 'link',
            //'id' => '1',
        ], $this->generateHeaders());

        $response->assertJsonValidationErrors([
            'model',
            'id',
        ]);
    }

    public function testRestoreWithMissingModel(): void
    {
        $this->setupTrashTestData();

        $response = $this->postJson('api/v1/trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ], $this->generateHeaders());

        $response->assertStatus(404);
    }
}
