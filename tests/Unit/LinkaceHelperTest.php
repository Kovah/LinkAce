<?php

namespace Tests\Unit;

use App\Helper\LinkAce;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkaceHelperTest extends TestCase
{
    /**
     * Test the titleFromURL() helper funtion with a valid URL
     * Will return the title of the Google frontpage: "Google"
     *
     * @return void
     */
    public function testTitleFromValidURL()
    {
        $url = 'https://google.com/';

        $result = LinkAce::getTitleFromURL($url);

        $this->assertEquals('Google', $result);
    }

    /**
     * Test the titleFromURL() helper funtion with an invalid URL
     * Will geturn just the host of the given URL
     *
     * @return void
     */
    public function testTitleFromInvalidURL()
    {
        $url = 'https://a-google-url-that-does-not-exist.comcom/';

        $result = LinkAce::getTitleFromURL($url);

        $this->assertEquals('a-google-url-that-does-not-exist.comcom', $result);
    }
}
