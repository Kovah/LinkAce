<?php

namespace Controller\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\API\ApiTestCase;

class GeneralApiTest extends ApiTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $_ENV['API_RATE_LIMIT'] = '120,1';
        parent::setUp();
    }

    public function testCustomRateLimit(): void
    {
        $response = $this->getJsonAuthorized('api/v2/links');

        $response->assertHeader('x-ratelimit-limit', 120);
        $response->assertHeader('x-ratelimit-remaining', 119);
    }
}
