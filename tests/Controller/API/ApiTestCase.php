<?php

namespace Tests\Controller\API;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first() ?: User::factory()->create();

        Queue::fake();
        Notification::fake();

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml, 200),
        ]);
    }

    /**
     * Send an authorized JSON request for the GET method.
     *
     * @param string $uri
     * @param array  $headers
     * @return TestResponse
     */
    public function getJsonAuthorized($uri, array $headers = []): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . $this->user->api_token;
        return $this->getJson($uri, $headers);
    }

    /**
     * Send an authorized JSON request for the POST method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @return TestResponse
     */
    public function postJsonAuthorized($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . $this->user->api_token;
        return $this->postJson($uri, $data, $headers);
    }

    /**
     * Send an authorized JSON request for the PATCH method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @return TestResponse
     */
    public function patchJsonAuthorized($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . $this->user->api_token;
        return $this->patchJson($uri, $data, $headers);
    }

    /**
     * Send an authorized JSON request for the DELETE method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @return TestResponse
     */
    public function deleteJsonAuthorized($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . $this->user->api_token;
        return $this->deleteJson($uri, $data, $headers);
    }
}
