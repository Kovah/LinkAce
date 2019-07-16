<?php

namespace Tests\Unit\Models;

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

        $tag = factory(Tag::class)->create();

        $changed_data = [
            'name' => 'Changed Tag Title',
        ];

        $updated_tag = TagRepository::update($tag, $changed_data);

        $asserted_data = array_merge($tag->toArray(), $changed_data);

        $this->assertDatabaseHas('tags', $asserted_data);
    }
}
