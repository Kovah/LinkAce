<?php

namespace Tests\Models;

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

        $this->user = User::factory()->create();
    }

    public function testValidTagCreation(): void
    {
        $this->be($this->user);

        $originalData = [
            'name' => 'Test Tag',
        ];

        $tag = TagRepository::create($originalData);

        $automatedData = [
            'id' => $tag->id,
            'user_id' => auth()->user()->id,
            'created_at' => $tag->created_at,
            'updated_at' => $tag->updated_at,
            'deleted_at' => null,
        ];

        $assertedData = array_merge($automatedData, $originalData);

        $this->assertDatabaseHas('tags', $assertedData);
    }
}
