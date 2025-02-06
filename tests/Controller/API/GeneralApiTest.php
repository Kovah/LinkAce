<?php

namespace Controller\API;

use Tests\Controller\API\ApiTestCase;

class GeneralApiTest extends ApiTestCase
{
    public function test_version(): void
    {
        $this->getJsonAuthorized('api/version')->assertJson(['version' => 'v1']);
    }
}
