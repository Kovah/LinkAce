<?php

namespace Tests\Unit;

use App\Helper\LinkAce;
use Tests\TestCase;

/**
 * Class LinkaceHelperTest
 *
 * @package Tests\Unit
 */
class LinkaceHelperTest extends TestCase
{
    /**
     * Test the titleFromURL() helper funtion with a valid URL
     * Will return the title of the Google frontpage: "Google"
     *
     * @return void
     */
    public function testTitleFromValidURL(): void
    {
        $url = 'https://google.com/';

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('Google', $result['title']);
    }

    /**
     * Test the titleFromURL() helper funtion with an invalid URL
     * Will geturn just the host of the given URL
     *
     * @return void
     */
    public function testTitleFromInvalidURL(): void
    {
        $url = 'https://a-google-url-that-does-not-exist.comcom/';

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('a-google-url-that-does-not-exist.comcom', $result['title']);
    }
}
