<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TrashControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    public function testValidTrashResponse(): void
    {
        $response = $this->get('trash');

        $response->assertStatus(200)
            ->assertSee('Search');
    }

    /*
     * Tests for clearing the trash
     */

    public function testValidTrashClearLinksResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/clear/links');

        $response->assertStatus(302);

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function testValidTrashClearTagsResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/clear/tags');

        $response->assertStatus(302);

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function testValidTrashClearListsResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/clear/lists');

        $response->assertStatus(302);

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function testValidTrashClearNotesResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/clear/notes');

        $response->assertStatus(302);

        $this->assertEquals(0, DB::table('notes')->count());
    }

    public function testTrashClearWithoutEntriesResponse(): void
    {
        $response = $this->get('trash/clear/links');

        $response->assertStatus(302);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals('No entries to be deleted.', $flashMessage['message']);
    }

    /*
     * Tests for restoring items
     */

    public function testValidRestoreLinkResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/restore/link/1');

        $response->assertStatus(302);
        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function testValidRestoreTagResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/restore/tag/1');

        $response->assertStatus(302);
        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function testValidRestoreListResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/restore/list/1');

        $response->assertStatus(302);
        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function testValidRestoreNoteResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/restore/note/1');

        $response->assertStatus(302);
        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function testInvalidRestoreResponse(): void
    {
        $this->setupTestData();

        $response = $this->get('trash/restore/link/1234');

        $response->assertStatus(404)
            ->assertSee('The item to be restored could not be found.');
    }

    public function testUnauthorizedRestoreResponse(): void
    {
        $this->setupTestData();

        $newUser = factory(User::class)->create();
        $this->actingAs($newUser);

        $response = $this->get('trash/restore/link/1');

        $response->assertStatus(403)
            ->assertSee('You are not allowed to restore this item.');
    }

    protected function setupTestData(): void
    {
        $tagExample = Tag::create([
            'name' => 'Examples',
            'user_id' => $this->user->id,
        ]);

        $listTest = LinkList::create([
            'name' => 'A Tests List',
            'user_id' => $this->user->id,
        ]);

        $linkExample = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://example.com',
            'title' => 'Very special site title',
            'description' => 'Some description for this site',
            'is_private' => true,
        ]);

        $linkExample->tags()->attach($tagExample->id);

        $linkExampleNote = Note::create([
            'user_id' => $this->user->id,
            'link_id' => $linkExample->id,
            'note' => 'Quisque placerat facilisis egestas cillum dolore.',
            'is_private' => false,
        ]);

        $linkTest = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'title' => 'Test Site',
            'description' => null,
            'is_private' => false,
        ]);

        $linkTest->lists()->attach($listTest->id);

        $tagExample->delete();
        $listTest->delete();
        $linkExample->delete();
        $linkExampleNote->delete();
        $linkTest->delete();
    }
}
