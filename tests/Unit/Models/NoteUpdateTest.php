<?php

namespace Tests\Unit\Models;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\NoteRepository;
use App\Repositories\TagRepository;
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

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidTagUpdate(): void
    {
        $this->be($this->user);

        $note = factory(Note::class)->create();

        $changedData = [
            'note' => 'Cras mattis iudicium purus sit amet fermentum.',
        ];

        $updatedNote = NoteRepository::update($note, $changedData);

        $this->assertEquals('Cras mattis iudicium purus sit amet fermentum.', $updatedNote->note);
    }
}
