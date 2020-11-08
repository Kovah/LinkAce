<?php

namespace Tests\Controller\App;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Controller\Traits\PreparesTrash;
use Tests\TestCase;

class TrashControllerTest extends TestCase
{
    use RefreshDatabase;
    use PreparesTrash;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testValidTrashResponse(): void
    {
        $response = $this->get('trash');

        $response->assertOk()
            ->assertSee('Search');
    }

    /*
     * Tests for clearing the trash
     */

    public function testValidTrashClearLinksResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'links',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function testValidTrashClearTagsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'tags',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function testValidTrashClearListsResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'lists',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function testValidTrashClearNotesResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'notes',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('notes')->count());
    }

    /*
     * Tests for restoring items
     */

    public function testValidRestoreLinkResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function testValidRestoreTagResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function testValidRestoreListResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'list',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function testValidRestoreNoteResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'note',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function testInvalidRestoreResponse(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            //'model' => 'link',
            //'id' => '1',
        ]);

        $response->assertSessionHasErrors([
            'model',
            'id',
        ]);
    }

    public function testRestoreWithMissingModel(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ]);

        $response->assertNotFound();
    }
}
