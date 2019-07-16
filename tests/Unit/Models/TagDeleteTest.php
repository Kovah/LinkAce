<?php

namespace Tests\Unit\Models;

use App\Models\Tag;
use App\Models\User;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagDeleteTest extends TestCase
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

        $tag = factory(Tag::class)->create();

        $deletion_result = TagRepository::delete($tag);

        $this->assertTrue($deletion_result);
    }
}
