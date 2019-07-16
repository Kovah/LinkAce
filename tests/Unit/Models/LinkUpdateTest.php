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

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidLinkUpdate()
    {
        $this->be($this->user);

        $link = factory(Link::class)->create();

        $changed_data = [
            'title' => 'This is a new title!',
        ];

        $updated_link = LinkRepository::update($link, $changed_data);

        $asserted_data = array_merge($link->toArray(), $changed_data);

        $this->assertDatabaseHas('links', $asserted_data);
    }
}
