<?php

namespace Tests\Unit\Helper;

use App\Helper\LinkAce;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
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
        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('DuckDuckGo', $result['title']);
    }

    /**
     * Test the titleFromURL() helper funtion with an invalid URL
     * Will geturn just the host of the given URL
     *
     * @return void
     */
    public function testTitleFromInvalidURL(): void
    {
        $url = 'https://duckduckgogo.comcom/';

        Http::fake([
            '*' => Http::response(null, 404),
        ]);

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgogo.comcom', $result['title']);
    }

    /**
     * Test the titleFromURL() helper funtion with an invalid URL
     * Will geturn just the host of the given URL
     *
     * @return void
     */
    public function testTitleFromURLwithoutProtocol(): void
    {
        $url = 'duckduckgo.com/about-us';

        Http::fake([
            '*' => Http::response(null, 404),
        ]);

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgo.com/about-us', $result['title']);
    }

    /**
     * Test the titleFromURL() helper funtion with an valid URL that returns
     * a certificate error.
     * Will return just the host of the given URL and issue a new flash message.
     *
     * @return void
     */
    public function testRequestError(): void
    {
        $url = 'https://self-signed.badssl.com/';

        Http::fake(function (Request $request) {
            throw new RequestException(
                'cURL error 60: SSL certificate problem: self signed certificate',
                new \GuzzleHttp\Psr7\Request('get', $request->url())
            );
        });

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('self-signed.badssl.com', $result['title']);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals(
            'The Link was added but an error occured when trying to request the URL, ' .
            'for example an invalid certificate. Details can be found in the logs.',
            $flashMessage['message']
        );
    }

    /**
     * Test the titleFromURL() helper funtion with an valid URL that is not
     * accessible due to connection errors, such as a refused connection for
     * a specific port.
     * Will return just the host of the given URL and issue a new flash message.
     *
     * @return void
     */
    public function testConnectionError(): void
    {
        $url = 'http://192.168.0.123:54623';

        Http::fake(function (Request $request) {
            throw new ConnectionException(
                'cURL error 7: Failed to connect to 192.168.0.123 port 54623: Connection refused'
            );
        });

        $result = LinkAce::getMetaFromURL($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('192.168.0.123', $result['title']);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals(
            'The Link was added but a connection error occured when trying to access the URL.' .
            ' Details can be found in the logs.',
            $flashMessage['message']
        );
    }
}
