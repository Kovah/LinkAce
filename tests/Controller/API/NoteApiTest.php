<?php

namespace Tests\Feature\Controller\API;

use App\Models\Link;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testMinimalCreateRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->postJson('api/v1/notes', [
            'link_id' => $link->id,
            'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Quae vero auctorem tractata ab fiducia dicuntur.', $databaseNote->note);
    }

    public function testFullCreateRequest(): void
    {
        $link = factory(Link::class)->create();

        $response = $this->postJson('api/v1/notes', [
            'link_id' => $link->id,
            'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
            'is_private' => true,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'note' => 'Quae vero auctorem tractata ab fiducia dicuntur.',
                'is_private' => true,
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Quae vero auctorem tractata ab fiducia dicuntur.', $databaseNote->note);
    }

    public function testInvalidCreateRequest(): void
    {
        $response = $this->postJson('api/v1/notes', [
            'link_id' => null,
            'note' => null,
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'link_id',
                'note',
            ]);
    }

    public function testUpdateRequest(): void
    {
        $link = factory(Link::class)->create();
        $note = factory(Note::class)->create([
            'link_id' => $link->id,
        ]);

        $response = $this->patchJson('api/v1/notes/1', [
            'link_id' => $link->id,
            'note' => 'Gallia est omnis divisa in partes tres, quarum.',
            'is_private' => false,
        ], $this->generateHeaders());

        $response->assertStatus(200)
            ->assertJson([
                'note' => 'Gallia est omnis divisa in partes tres, quarum.',
            ]);

        $databaseNote = Note::first();

        $this->assertEquals('Gallia est omnis divisa in partes tres, quarum.', $databaseNote->note);
    }

    public function testInvalidUpdateRequest(): void
    {
        $note = factory(Note::class)->create();

        $response = $this->patchJson('api/v1/notes/1', [
            //
        ], $this->generateHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'link_id',
                'note',
            ]);
    }

    public function testUpdateRequestNotFound(): void
    {
        $response = $this->patchJson('api/v1/notes/1', [
            'link_id' => 1,
            'note' => 'Sed haec quis possit intrepidus aestimare tellus.',
            'is_private' => false,
        ], $this->generateHeaders());

        $response->assertStatus(404);
    }

    public function testDeleteRequest(): void
    {
        $note = factory(Note::class)->create();

        $response = $this->deleteJson('api/v1/notes/1', [], $this->generateHeaders());

        $response->assertStatus(200);

        $this->assertEquals(0, Note::count());
    }

    public function testDeleteRequestNotFound(): void
    {
        $response = $this->deleteJson('api/v1/notes/1', [], $this->generateHeaders());

        $response->assertStatus(404);
    }

    protected function generateHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->user->api_token,
        ];
    }
}
