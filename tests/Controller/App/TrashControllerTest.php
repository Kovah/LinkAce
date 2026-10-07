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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_valid_trash_response(): void
    {
        $response = $this->get('trash');

        $response->assertOk()
            ->assertSee('Search');
    }

    public function test_trash_does_not_disclose_a_link_the_user_cannot_view(): void
    {
        $otherUser = User::factory()->create();
        $privateLink = Link::factory()->for($otherUser)->create([
            'url' => 'https://victim-private.example/secret',
            'title' => 'VICTIM PRIVATE LINK',
            'visibility' => 3,
        ]);

        // A note of the current user pointing at a link they must not see, as left
        // behind by the note update flaw
        $note = Note::factory()->for($this->user)->create(['link_id' => $privateLink->id]);
        $note->delete();

        $this->get('trash')
            ->assertOk()
            ->assertDontSee('https://victim-private.example/secret')
            ->assertDontSee('VICTIM PRIVATE LINK');
    }

    public function test_trash_renders_notes_whose_link_was_deleted_too(): void
    {
        $link = Link::factory()->for($this->user)->create();
        $note = Note::factory()->for($this->user)->create(['link_id' => $link->id]);
        $note->delete();
        $link->delete();

        $this->get('trash')->assertOk();
    }

    /*
     * Tests for clearing the trash
     */

    public function test_valid_trash_clear_links_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'links',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('links')->count());
    }

    public function test_valid_trash_clear_tags_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'tags',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('tags')->count());
    }

    public function test_valid_trash_clear_lists_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/clear', [
            'model' => 'lists',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(0, DB::table('lists')->count());
    }

    public function test_valid_trash_clear_notes_response(): void
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

    public function test_valid_restore_link_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Link::find(1)->deleted_at);
    }

    public function test_valid_restore_tag_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'tag',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Tag::find(1)->deleted_at);
    }

    public function test_valid_restore_list_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'list',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, LinkList::find(1)->deleted_at);
    }

    public function test_valid_restore_note_response(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'note',
            'id' => '1',
        ]);

        $response->assertRedirect('trash');

        $this->assertEquals(null, Note::find(1)->deleted_at);
    }

    public function test_valid_restore_note_response_with_redirect_to_model(): void
    {
        $link = Link::factory()->for($this->user)->create();
        $note = Note::factory()->for($this->user)->create(['link_id' => $link->id]);
        $note->delete();

        $response = $this->post('trash/restore', [
            'model' => 'note',
            'id' => $note->id,
            'redirect_to_model' => '1',
        ]);

        $response->assertRedirect('links/' . $link->id);
    }

    public function test_restore_note_with_redirect_to_model_falls_back_to_trash(): void
    {
        // The link of the note is still in the trash, so there is nothing to show
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'note',
            'id' => '1',
            'redirect_to_model' => '1',
        ]);

        $response->assertRedirect('trash');
    }

    public function test_invalid_restore_response(): void
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

    public function test_restore_with_missing_model(): void
    {
        $this->setupTrashTestData();

        $response = $this->post('trash/restore', [
            'model' => 'link',
            'id' => '1345235',
        ]);

        $response->assertNotFound();
    }
}
