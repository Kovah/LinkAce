<?php

namespace Tests\Controller\Models;

use App\Models\Link;
use App\Models\Note;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    public function testMinimalStoreRequest(): void
    {
        $link = Link::factory()->create();

        $response = $this->post('notes', [
            'link_id' => $link->id,
            'note' => 'Lorem ipsum dolor',
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/1');

        $this->assertEquals('Lorem ipsum dolor', $link->notes()->first()->note);
    }

    public function testStoreRequestWithPrivateDefault(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'notes_private_default',
            'value' => '1',
        ]);

        $link = Link::factory()->create();

        $response = $this->post('notes', [
            'link_id' => $link->id,
            'note' => 'Lorem ipsum dolor',
            'is_private' => usersettings('notes_private_default'),
        ]);

        $response->assertRedirect('links/1');

        $this->assertTrue($link->notes()->first()->is_private);
    }

    public function testStoreRequestWithMarkdown(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'markdown_for_text',
            'value' => '1',
        ]);

        $link = Link::factory()->create();

        $response = $this->post('notes', [
            'link_id' => $link->id,
            'note' => 'Lorem _ipsum dolor_',
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/1');

        $response = $this->get('links/1');
        $response->assertSee('Lorem <em>ipsum dolor</em>', false);
    }

    public function testValidationErrorForCreate(): void
    {
        $link = Link::factory()->create();

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

        $response->assertNotFound();
    }

    public function testEditView(): void
    {
        Note::factory()->create();

        $response = $this->get('notes/1/edit');

        $response->assertOk()
            ->assertSee('Edit Note');
    }

    public function testInvalidEditRequest(): void
    {
        $response = $this->get('notes/1/edit');

        $response->assertNotFound();
    }

    public function testUpdateResponse(): void
    {
        $baseNote = Note::factory()->create();

        $response = $this->patch('notes/1', [
            'link_id' => $baseNote->link_id,
            'note' => 'Lorem ipsum dolor est updated',
            'is_private' => '0',
        ]);

        $response->assertRedirect('links/' . $baseNote->link_id);

        $updatedLink = $baseNote->fresh();

        $this->assertEquals('Lorem ipsum dolor est updated', $updatedLink->note);
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->patch('notes/1', [
            'link_id' => '1',
            'note' => 'Lorem ipsum dolor est updated',
            'is_private' => '0',
        ]);

        $response->assertNotFound();
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseNote = Note::factory()->create();

        $response = $this->patch('notes/1', [
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
        $link = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $note = Note::factory()->create([
            'link_id' => $link->id,
        ]);

        $response = $this->deleteJson('notes/1');

        $response->assertRedirect('links/' . $note->link_id);

        $databaseNote = Note::withTrashed()->first();

        $this->assertNotNull($databaseNote->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->deleteJson('notes/1');

        $response->assertNotFound();
    }
}
