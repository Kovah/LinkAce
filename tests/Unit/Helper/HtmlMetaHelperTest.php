<?php

namespace Tests\Unit\Helper;

use App\Helper\HtmlMeta;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Class HtmlMetaHelperTest
 *
 * @package Tests\Unit
 */
class HtmlMetaHelperTest extends TestCase
{
    /**
     * Test the titleFromURL() helper funtion with a valid URL
     * Will return the title of the Google frontpage: "Google"
     *
     * @return void
     */
    public function testTitleFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta name="test" content="Bla">' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('DuckDuckGo', $result['title']);
        $this->assertEquals('This an example description', $result['description']);
        $this->assertTrue($result['success']);
    }

    /**
     * Test the titleFromURL() helper funtion with a valid URL
     * Will return the title of the Google frontpage: "Google"
     *
     * @return void
     */
    public function testAlternativeDescriptionFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta property="og:description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('DuckDuckGo', $result['title']);
        $this->assertEquals('This an example description', $result['description']);
        $this->assertTrue($result['success']);
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

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgogo.comcom', $result['title']);
        $this->assertFalse($result['success']);
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

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgo.com/about-us', $result['title']);
        $this->assertFalse($result['success']);
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

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('self-signed.badssl.com', $result['title']);
        $this->assertFalse($result['success']);

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

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('192.168.0.123', $result['title']);
        $this->assertFalse($result['success']);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals(
            'The Link was added but a connection error occured when trying to access the URL.' .
            ' Details can be found in the logs.',
            $flashMessage['message']
        );
    }
}
