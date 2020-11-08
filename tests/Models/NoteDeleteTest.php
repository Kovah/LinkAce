<?php

namespace Tests\Models;

use App\Models\Note;
use App\Models\User;
use App\Repositories\NoteRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NoteDeleteTest extends TestCase
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

    public function testValidCategoryCreation(): void
    {
        $this->be($this->user);

        $note = Note::factory()->create();

        $deletionResult = NoteRepository::delete($note);

        $this->assertTrue($deletionResult);
    }
}
