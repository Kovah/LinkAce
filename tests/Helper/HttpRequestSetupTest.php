<?php

namespace Tests\Helper;

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
                throw new ConnectionException('cURL error 7: Failed to connect to www.heise.de port 443 after 0 ms');
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
}
