<?php

namespace Tests\Models;

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

        $this->user = User::factory()->create();
    }

    public function testValidLinkUpdate(): void
    {
        $this->be($this->user);

        $link = Link::factory()->create();

        $changedData = [
            'title' => 'This is a new title!',
        ];

        $updatedLink = LinkRepository::update($link, $changedData);

        $this->assertEquals('This is a new title!', $updatedLink->title);
    }
}
