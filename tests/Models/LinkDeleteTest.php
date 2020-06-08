<?php

namespace Tests\Models;

use App\Models\Link;
use App\Models\User;
use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkDeleteTest extends TestCase
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

    public function testValidCategoryCreation(): void
    {
        $this->be($this->user);

        $link = factory(Link::class)->create();

        $deletionResult = LinkRepository::delete($link);

        $this->assertTrue($deletionResult);
    }
}
