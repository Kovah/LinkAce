<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagCreateTest extends TestCase
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
    public function testValidTagCreation(): void
    {
        $this->be($this->user);

        $original_data = [
            'name' => 'Test Tag',
        ];

        $tag = TagRepository::create($original_data);

        $automated_data = [
            'id' => $tag->id,
            'user_id' => auth()->user()->id,
            'created_at' => $tag->created_at,
            'updated_at' => $tag->updated_at,
            'deleted_at' => null,
        ];

        $asserted_data = array_merge($automated_data, $original_data);

        $this->assertDatabaseHas('tags', $asserted_data);
    }
}
