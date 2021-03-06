<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testMinimalCreateRequest(): void
    {
        $link = Link::factory()->create();

        $response = $this->postJsonAuthorized('api/v1/notes', [
            'link_id' => $link->id,
            'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
        ]);

        $response->assertOk()
            ->assertJson([
                'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Quae vero auctorem tractata ab fiducia dicuntur.', $databaseNote->note);
    }

    public function testFullCreateRequest(): void
    {
        $link = Link::factory()->create();

        $response = $this->postJsonAuthorized('api/v1/notes', [
            'link_id' => $link->id,
            'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
            'is_private' => true,
        ]);

        $response->assertOk()
            ->assertJson([
                'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
                'is_private' => true,
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Quae vero auctorem tractata ab fiducia dicuntur.', $databaseNote->note);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJsonAuthorized('api/v1/notes', [
            'link_id' => null,
            'note' => null,
        ]);

        $response->assertJsonValidationErrors([
            'link_id' => 'The link id field is required.',
            'note' => 'The note field is required.',
        ]);
    }

    public function testUpdateRequest(): void
    {
        $link = Link::factory()->create();
        Note::factory()->create([
            'link_id' => $link->id,
        ]);

        $response = $this->patchJsonAuthorized('api/v1/notes/1', [
            'note' => 'Gallia est omnis divisa in partes tres, quarum.',
            'is_private' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'note' => 'Gallia est omnis divisa in partes tres, quarum.',
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Gallia est omnis divisa in partes tres, quarum.', $databaseNote->note);
    }

    public function testInvalidUpdateRequest(): void
    {
        Note::factory()->create();

        $response = $this->patchJsonAuthorized('api/v1/notes/1', [
            //
        ]);

        $response->assertJsonValidationErrors([
            'note' => 'The note field is required.',
        ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJsonAuthorized('api/v1/notes/1', [
            'note' => 'Sed haec quis possit intrepidus aestimare tellus.',
            'is_private' => false,
        ]);

        $response->assertNotFound();
    }

    public function testDeleteRequest(): void
    {
        Note::factory()->create();

        $response = $this->deleteJsonAuthorized('api/v1/notes/1', []);

        $response->assertOk();

        $this->assertEquals(0, Note::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJsonAuthorized('api/v1/notes/1');

        $response->assertNotFound();
    }
}
