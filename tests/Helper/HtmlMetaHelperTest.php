<?php

namespace Tests\Helper;

use App\Helper\HtmlMeta;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HtmlMetaHelperTest extends TestCase
{
    /**
     * Test the titleFromURL() helper function with a valid URL
     * Will return the title of the DuckDuckGo frontpage: "DuckDuckGo"
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
     * Test the titleFromURL() helper function with a valid URL
     * Will return the title of the DuckDuckGo frontpage: "DuckDuckGo".
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
     * Test the titleFromURL() helper function with an invalid URL
     * Will return just the host of the given URL.
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
     * Test the titleFromURL() helper function with an invalid URL
     * Will return just the host of the given URL.
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
     * Test the titleFromURL() helper function with an valid URL that returns
     * a certificate error.
     * Will return just the host of the given URL and issue a new flash message.
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

        $result = HtmlMeta::getFromUrl($url, true);

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
     * Test the titleFromURL() helper function with an valid URL that is not
     * accessible due to connection errors, such as a refused connection for
     * a specific port.
     * Will return just the host of the given URL and issue a new flash message.
     */
    public function testConnectionError(): void
    {
        $url = 'http://192.168.0.123:54623';

        Http::fake(function (Request $request) {
            throw new ConnectionException(
                'cURL error 7: Failed to connect to 192.168.0.123 port 54623: Connection refused'
            );
        });

        $result = HtmlMeta::getFromUrl($url, true);

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

    /**
     * Test if the helper is able to convert a non-UTF-8 title into UTF-8.
     * hex2bin('3c7469746c653ecfe8eae0e1f33c2f7469746c653e') translates to
     * '<title>Пикабу</title>' in this case. 'Пикабу' must be correctly parsed
     * and converted into UTF-8 as the title.
     */
    public function testMetaEncodingWithCharsetAvailable(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<meta charset="windows-1251">' .
            hex2bin('3c7469746c653ecfe8eae0e1f33c2f7469746c653e') .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('Пикабу', $result['title']);
    }

    /**
     * Test if the helper correctly discards the title if
     * a) no charset meta tag is present and
     * b) the title is detected as a non-UTF-8 string.
     *
     * The returned title must the the domain of the original URL.
     */
    public function testMetaEncodingWithCharsetMissing(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            hex2bin('3c7469746c653ecfe8eae0e1f33c2f7469746c653e') .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgo.com', $result['title']);
    }

    /**
     * Test the titleFromURL() helper function with a valid URL.
     * Should return the host as the title because conversion is not possible
     * in this case.
     */
    public function testMetaEncodingWithIncorrectCharset(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta charset="utf-8,windows-1251">' .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $url = 'https://duckduckgo.com/';
        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgo.com', $result['title']);
        $this->assertTrue($result['success']);
    }

    /**
     * Test the HTML Meta helper function with a valid URL and the charset
     * defined in the content-type header.
     * The hex2bin('3c6d6574612...') translates to '<meta name="description" content="Qualität">'
     * in this case. 'Qualität' must be correctly parsed and converted into
     * UTF-8 as the description.
     */
    public function testMetaEncodingWithContentType(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            hex2bin('3c6d657461206e616d653d226465736372697074696f6e2220636f6e74656e743d225175616c6974e474223e') .
            '</head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200, [
                'Content-Type' => 'text/html; charset=iso-8859-1'
            ]),
        ]);

        $url = 'https://encoding-test.com/';

        $result = HtmlMeta::getFromUrl($url);

        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('Qualität', $result['description']);
    }
}
