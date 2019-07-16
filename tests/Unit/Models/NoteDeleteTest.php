<?php

namespace Tests\Unit\Models;

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

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidCategoryCreation(): void
    {
        $this->be($this->user);

        $note = factory(Note::class)->create();

        $deletion_result = NoteRepository::delete($note);

        $this->assertTrue($deletion_result);
    }
}
