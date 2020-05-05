<?php

namespace Tests\Unit\Models;

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

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidNoteCreation(): void
    {
        $this->be($this->user);

        $link = factory(Link::class)->create();

        $original_data = [
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt.',
            'link_id' => $link->id,
        ];

        $note = NoteRepository::create($original_data);

        $automated_data = [
            'id' => $note->id,
            'user_id' => auth()->user()->id,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
            'deleted_at' => null,
        ];

        $asserted_data = array_merge($automated_data, $original_data);

        $this->assertDatabaseHas('notes', $asserted_data);
    }
}
