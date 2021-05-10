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
    /*
     * Test the HtmlMeta helper with a regular website containing some meta information. Must properly return the
     * information extracted from the meta.
     */
    public function testMetaFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta name="test" content="Bla">' .
            '<meta name="description" content="This an example description">' .
            '<meta property="og:image" content="https://duckduckgo.com/assets/logo_social-media.png">' .
            '</head></html>';

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/';

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertEquals('DuckDuckGo', $result['title']);
        $this->assertEquals('This an example description', $result['description']);
        $this->assertEquals('https://duckduckgo.com/assets/logo_social-media.png', $result['thumbnail']);
        $this->assertTrue($result['success']);
    }

    /*
     * Test the HtmlMeta helper with an alternative description provided by the og:description tag.
     */
    public function testAlternativeDescriptionFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta property="og:description" content="This an example description">' .
            '</head></html>';

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/';

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertEquals('DuckDuckGo', $result['title']);
        $this->assertEquals('This an example description', $result['description']);
        $this->assertTrue($result['success']);
    }

    public function testThumbnailWithoutHostFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<meta property="og:image" content="/assets/logo_social-media.png">' .
            '</head></html>';

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/about-us?utm_source=foo';

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertEquals('https://duckduckgo.com/assets/logo_social-media.png', $result['thumbnail']);
        $this->assertTrue($result['success']);
    }

    /*
     * Test the HtmlMeta helper with a YouTube link. Must return a special YouTube thumbnail.
     */
    public function testYoutubeThumbnailFromValidURL(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>YouTube</title>' .
            '</head></html>';

        Http::fake(['*' => Http::response($testHtml)]);

        // Regular YouTube link
        $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('YouTube', $result['title']);
        $this->assertEquals('https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg', $result['thumbnail']);
        $this->assertTrue($result['success']);

        // Short Youtu.be sharing link
        $url = 'https://youtu.be/dQw4w9WgXcQ';
        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('YouTube', $result['title']);
        $this->assertEquals('https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg', $result['thumbnail']);
        $this->assertTrue($result['success']);
    }

    /*
     * Test the HtmlMeta helper with an invalid URL. Must return the hostname as the title.
     */
    public function testTitleFromInvalidURL(): void
    {
        $url = 'https://duckduckgogo.comcom/';

        Http::fake(['*' => Http::response('', 404)]);

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgogo.comcom', $result['title']);
        $this->assertFalse($result['success']);
    }

    /*
     * Test the HtmlMeta helper with an invalid URL. Must return the hostname as the title.
     */
    public function testTitleFromUrlWithoutProtocol(): void
    {
        $url = 'duckduckgo.com/about-us';

        Http::fake([
            '*' => Http::response(null, 404),
        ]);

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('duckduckgo.com/about-us', $result['title']);
        $this->assertFalse($result['success']);
    }

    /*
     * Test the HtmlMeta helper with an URL returning a certificate error. Must return the hostname as the title.
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

        $result = (new HtmlMeta)->getFromUrl($url, true);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('self-signed.badssl.com', $result['title']);
        $this->assertFalse($result['success']);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals(
            'The Link was added but an error occurred when trying to request the URL, for example an invalid certificate. Details can be found in the logs.',
            $flashMessage['message']
        );
    }

    /*
     * Test the HtmlMeta helper with an URL that is not accessible due to connection errors, such as a refused
     * connection for a specific port. Must return the hostname as the title.
     */
    public function testConnectionError(): void
    {
        $url = 'http://192.168.0.123:54623';

        Http::fake(function (Request $request) {
            throw new ConnectionException(
                'cURL error 7: Failed to connect to 192.168.0.123 port 54623: Connection refused'
            );
        });

        $result = (new HtmlMeta)->getFromUrl($url, true);

        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('192.168.0.123', $result['title']);
        $this->assertFalse($result['success']);

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals(
            'The Link was added but an error occurred when trying to request the URL, for example an invalid certificate. Details can be found in the logs.',
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

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/';

        $result = (new HtmlMeta)->getFromUrl($url);

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

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/';

        $result = (new HtmlMeta)->getFromUrl($url);

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

        Http::fake(['*' => Http::response($testHtml)]);

        $url = 'https://duckduckgo.com/';
        $result = (new HtmlMeta)->getFromUrl($url);

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
                'Content-Type' => 'text/html; charset=iso-8859-1',
            ]),
        ]);

        $url = 'https://encoding-test.com/';

        $result = (new HtmlMeta)->getFromUrl($url);

        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('Qualität', $result['description']);
    }
}
