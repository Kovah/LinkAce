<?php

namespace Tests\Helper;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpRequestSetupTest extends TestCase
{
    public function test_connect_error_is_retried_once_with_ipv4_only(): void
    {
        $curlOptions = [];

        Http::fake(function ($request, $options) use (&$curlOptions) {
            $curlOptions[] = $options['curl'] ?? [];

            if (count($curlOptions) === 1) {
                throw $this->connectException($request->url(), 'cURL error 7: Failed to connect to www.heise.de port 443 after 0 ms', CURLE_COULDNT_CONNECT);
            }

            return Http::response('ok', 200);
        });

        $response = setupHttpRequest()->get('https://www.heise.de');

        $this->assertTrue($response->successful());
        $this->assertCount(2, $curlOptions);
        $this->assertArrayNotHasKey(CURLOPT_IPRESOLVE, $curlOptions[0]);
        $this->assertSame(CURL_IPRESOLVE_V4, $curlOptions[1][CURLOPT_IPRESOLVE]);
    }

    public function test_other_connection_errors_are_not_retried(): void
    {
        $attempts = 0;

        Http::fake(function () use (&$attempts) {
            $attempts++;

            throw new ConnectionException('cURL error 28: Operation timed out after 10001 milliseconds');
        });

        try {
            setupHttpRequest()->get('https://example.com');
            $this->fail('The connection error should have been thrown.');
        } catch (ConnectionException) {
            $this->assertSame(1, $attempts);
        }
    }

    public function test_failed_responses_are_not_retried_and_do_not_throw(): void
    {
        $attempts = 0;

        Http::fake(function () use (&$attempts) {
            $attempts++;

            return Http::response('', 404);
        });

        $response = setupHttpRequest()->get('https://example.com');

        $this->assertSame(404, $response->status());
        $this->assertSame(1, $attempts);
    }

    public function test_errors_with_a_number_between_70_and_79_are_not_retried(): void
    {
        $attempts = 0;

        Http::fake(function ($request, $options) use (&$attempts) {
            $attempts++;

            throw $this->connectException($request->url(), 'cURL error 77: error setting certificate verify locations', 77);
        });

        try {
            setupHttpRequest()->get('https://example.com');
            $this->fail('The connection error should have been thrown.');
        } catch (ConnectionException) {
            $this->assertSame(1, $attempts);
        }
    }

    public function test_ipv6_literal_urls_are_not_retried(): void
    {
        $attempts = 0;

        Http::fake(function ($request, $options) use (&$attempts) {
            $attempts++;

            throw $this->connectException($request->url(), 'cURL error 7: Failed to connect', CURLE_COULDNT_CONNECT);
        });

        try {
            setupHttpRequest()->get('http://[2001:db8::1]/');
            $this->fail('The connection error should have been thrown.');
        } catch (ConnectionException) {
            $this->assertSame(1, $attempts);
        }
    }

    public function test_retry_is_refused_when_only_ipv6_addresses_were_validated(): void
    {
        $attempts = 0;

        Http::fake(function ($request, $options) use (&$attempts) {
            $attempts++;

            throw $this->connectException($request->url(), 'cURL error 7: Failed to connect', CURLE_COULDNT_CONNECT);
        });

        config(['html-meta.block_private_ips' => false]);

        $request = applyPinnedIpProtection(setupHttpRequest())
            ->withOptions(['curl' => [CURLOPT_RESOLVE => ['example.com:443:[2001:db8::1]']]]);

        try {
            $request->get('https://example.com');
            $this->fail('The connection error should have been thrown.');
        } catch (ConnectionException) {
            $this->assertSame(1, $attempts);
        }
    }

    public function test_retry_keeps_the_validated_ipv4_pin(): void
    {
        $curlOptions = [];

        Http::fake(function ($request, $options) use (&$curlOptions) {
            $curlOptions[] = $options['curl'] ?? [];

            if (count($curlOptions) === 1) {
                throw $this->connectException($request->url(), 'cURL error 7: Failed to connect', CURLE_COULDNT_CONNECT);
            }

            return Http::response('ok', 200);
        });

        $pins = ['example.com:443:[2001:db8::1]', 'example.com:443:93.184.216.34'];
        config(['html-meta.block_private_ips' => false]);

        $request = applyPinnedIpProtection(setupHttpRequest())
            ->withOptions(['curl' => [CURLOPT_RESOLVE => $pins]]);

        $this->assertTrue($request->get('https://example.com')->successful());
        $this->assertCount(2, $curlOptions);
        $this->assertSame($pins, $curlOptions[1][CURLOPT_RESOLVE]);
        $this->assertSame(CURL_IPRESOLVE_V4, $curlOptions[1][CURLOPT_IPRESOLVE]);
    }

    private function connectException(string $url, string $message, int $errno): ConnectionException
    {
        $psrRequest = new Request('GET', $url);

        return new ConnectionException($message, 0, new ConnectException($message, $psrRequest, null, ['errno' => $errno]));
    }
}
