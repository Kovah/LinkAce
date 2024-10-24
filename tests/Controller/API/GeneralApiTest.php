<?php

namespace Controller\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\API\ApiTestCase;
use Tests\Controller\Traits\PreparesTestData;

class GeneralApiTest extends ApiTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $_ENV['API_RATE_LIMIT'] = '120,1';
    }

    public function testCustomRateLimit(): void
    {
        $response = $this->getJsonAuthorized('api/v2/links');

        $response->assertHeader('x-ratelimit-limit', 120);
        $response->assertHeader('x-ratelimit-remaining', 119);
    }
}
