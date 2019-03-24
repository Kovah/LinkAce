<?php

namespace Tests\Unit\Helper;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class HelperFunctionsTest
 *
 * @package Tests\Unit\Helper
 */
class HelperFunctionsTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    /** @var Link */
    private $link;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->link = factory(Link::class)->create();
    }

    /**
     * Test the saveToArchive() helper funtion with a valid URL.
     * Should return true.
     *
     * @return void
     */
    public function testValidWaybackLink(): void
    {
        $expected = 'https://web.archive.org/web/*/' . $this->link->url;

        $link = waybackLink($this->link);

        $this->assertEquals($expected, $link);
    }

    /**
     * Test the saveToArchive() helper funtion with an invalid URL.
     * Will return false.
     *
     * @return void
     */
    public function testInvalidWaybackLink(): void
    {
        $url = 'not an URL';

        $link = waybackLink($url);

        $this->assertNull($link);
    }
}
