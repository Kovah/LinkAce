<?php

namespace Tests\Models;

use App\Models\Tag;
use App\Models\User;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagUpdateTest extends TestCase
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

        $tag = Tag::factory()->create();

        $changedData = [
            'name' => 'Changed Tag Title',
        ];

        $updatedTag = TagRepository::update($tag, $changedData);

        $this->assertEquals('Changed Tag Title', $updatedTag->name);
    }
}
