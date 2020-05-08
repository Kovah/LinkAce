<?php

namespace Tests\Unit\Models;

use App\Models\Link;
use App\Models\User;
use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkUpdateTest extends TestCase
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
    public function testValidLinkUpdate(): void
    {
        $this->be($this->user);

        $link = factory(Link::class)->create();

        $changedData = [
            'title' => 'This is a new title!',
        ];

        $updatedLink = LinkRepository::update($link, $changedData);

        $this->assertEquals('This is a new title!', $updatedLink->title);
    }
}
