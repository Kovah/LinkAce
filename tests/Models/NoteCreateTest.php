<?php

namespace Tests\Models;

use App\Models\Link;
use App\Models\User;
use App\Repositories\NoteRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NoteCreateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testValidNoteCreation(): void
    {
        $this->be($this->user);

        $link = Link::factory()->create();

        $originalData = [
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt.',
            'link_id' => $link->id,
        ];

        $note = NoteRepository::create($originalData);

        $automatedData = [
            'id' => $note->id,
            'user_id' => auth()->user()->id,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
            'deleted_at' => null,
        ];

        $assertedData = array_merge($automatedData, $originalData);

        $this->assertDatabaseHas('notes', $assertedData);
    }
}
