<?php

namespace Tests\Controller\App;

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

        $response = $this->post('trash/clear', [
            'model' => 'links'
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function testValidTrashClearTagsResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/clear', [
            'model' => 'tags'
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function testValidTrashClearListsResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/clear', [
            'model' => 'lists'
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function testValidTrashClearNotesResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/clear', [
            'model' => 'notes'
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('notes')->count());
    }

    /*
     * Tests for restoring items
     */

    public function testValidRestoreLinkResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');
        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function testValidRestoreTagResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');
        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function testValidRestoreListResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/restore', [
            'model' => 'list',
            'id' => '1',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function testValidRestoreNoteResponse(): void
    {
        $this->setupTestData();

        $response = $this->post('trash/restore', [
            'model' => 'note',
            'id' => '1',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function testInvalidRestoreResponse(): void
    {
        $this->setupTestData();

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
        $this->setupTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ]);

        $response->assertStatus(404);
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
