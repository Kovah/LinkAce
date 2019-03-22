<?php

namespace Tests\Unit\Models;

use App\Helper\LinkAce;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkCreateTest extends TestCase
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
    public function testValidLinkCreation()
    {
        $url = 'https://google.com/';

        $meta = LinkAce::getMetaFromURL($url);

        $original_data = [
            'user_id' => $this->user->id,
            'category_id' => null,
            'url' => $url,
            'title' => $meta['title'],
            'description' => $meta['description'],
            'icon' => LinkIconMapper::mapLink($url),
            'is_private' => false,
            'status' => 1,
        ];

        $link = Link::create($original_data);

        $automated_data = [
            'id' => $link->id,
            'created_at' => $link->created_at,
            'updated_at' => $link->updated_at,
            'deleted_at' => null,
        ];

        $asserted_data = array_merge($automated_data, $original_data);

        // Assert that the database now has a category called 'Test Category Number 1'
        $this->assertDatabaseHas('links', $asserted_data);
    }
}
