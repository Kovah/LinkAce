<?php

namespace Tests\Feature\Controller\Models;

use App\Models\Link;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user);
    }

    public function testMinimalStoreRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->post('notes', [
            'link_id' => $link->id,
            'note' => 'Lorem ipsum dolor',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/1');

        $this->assertEquals('Lorem ipsum dolor', $link->notes()->first()->note);
    }

    public function testValidationErrorForCreate(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->post('notes', [
            'link_id' => $link->id,
            'note' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'note',
        ]);
    }

    public function testStoreRequestForMissingLink(): void
    {
        $response = $this->post('notes', [
            'link_id' => '1',
            'note' => 'Lorem ipsum dolor',
            'is_private' => '0',
        ]);

        $response->assertStatus(404);
    }

    public function testEditView(): void
    {
        factory(Note::class)->create();

        $response = $this->get('notes/1/edit');

        $response->assertStatus(200)
            ->assertSee('Edit Note');
    }

    public function testInvalidEditRequest(): void
    {
        $response = $this->get('notes/1/edit');

        $response->assertStatus(404);
    }

    public function testUpdateResponse(): void
    {
        $baseNote = factory(Note::class)->create();

        $response = $this->post('notes/1', [
            '_method' => 'patch',
            'link_id' => $baseNote->link_id,
            'note' => 'Lorem ipsum dolor est updated',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/' . $baseNote->link_id);

        $updatedLink = $baseNote->fresh();

        $this->assertEquals('Lorem ipsum dolor est updated', $updatedLink->note);
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->post('notes/1', [
            '_method' => 'patch',
            'link_id' => '1',
            'note' => 'Lorem ipsum dolor est updated',
            'is_private' => '0',
        ]);

        $response->assertStatus(404);
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseNote = factory(Note::class)->create();

        $response = $this->post('notes/1', [
            '_method' => 'patch',
            'note_id' => $baseNote->id,
            //'note' => 'Lorem ipsum dolor est updated',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'note',
        ]);
    }

    public function testDeleteResponse(): void
    {
        $link = factory(Note::class)->create([
            'user_id' => $this->user->id,
        ]);

        $note = factory(Note::class)->create([
            'link_id' => $link->id,
        ]);

        $response = $this->post('notes/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('links/' . $note->link_id);

        $databaseNote = Note::withTrashed()->first();

        $this->assertNotNull($databaseNote->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->post('notes/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(404);
    }
}
