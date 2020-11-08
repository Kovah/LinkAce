<?php

namespace Tests\Models;

use App\Models\Note;
use App\Models\User;
use App\Repositories\NoteRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NoteUpdateTest extends TestCase
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

    public function testValidTagUpdate(): void
    {
        $this->be($this->user);

        $note = Note::factory()->create();

        $changedData = [
            'note' => 'Cras mattis iudicium purus sit amet fermentum.',
        ];

        $updatedNote = NoteRepository::update($note, $changedData);

        $this->assertEquals('Cras mattis iudicium purus sit amet fermentum.', $updatedNote->note);
    }
}
