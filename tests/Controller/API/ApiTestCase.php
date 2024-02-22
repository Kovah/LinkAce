<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected User $user;
    protected string $accessToken;
    protected ?User $systemUser = null;
    protected ?string $systemAccessToken = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::notSystem()->first() ?: User::factory()->create();
        $this->accessToken = $this->user->createToken('api-test', [ApiToken::ABILITY_USER_ACCESS])->plainTextToken;

        Queue::fake();
        Notification::fake();

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml),
        ]);
    }

    protected function createSystemToken(array $abilities = []): void
    {
        $this->systemUser = User::getSystemUser();
        $abilities[] = ApiToken::ABILITY_SYSTEM_ACCESS;
        $this->systemAccessToken = $this->systemUser->createToken('api-test', $abilities)->plainTextToken;
    }

    /**
     * Send an authorized JSON request for the GET method.
     *
     * @param string $uri
     * @param array  $headers
     * @param bool   $useSystemToken
     * @return TestResponse
     */
    public function getJsonAuthorized(string $uri, array $headers = [], bool $useSystemToken = false): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . ($useSystemToken ? $this->systemAccessToken : $this->accessToken);
        return $this->getJson($uri, $headers);
    }

    /**
     * Send an authorized JSON request for the POST method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $useSystemToken
     * @return TestResponse
     */
    public function postJsonAuthorized(
        string $uri,
        array $data = [],
        array $headers = [],
        bool $useSystemToken = false
    ): TestResponse {
        $headers['Authorization'] = 'Bearer ' . ($useSystemToken ? $this->systemAccessToken : $this->accessToken);
        return $this->postJson($uri, $data, $headers);
    }

    /**
     * Send an authorized JSON request for the PATCH method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $useSystemToken
     * @return TestResponse
     */
    public function patchJsonAuthorized(
        string $uri,
        array $data = [],
        array $headers = [],
        bool $useSystemToken = false
    ): TestResponse {
        $headers['Authorization'] = 'Bearer ' . ($useSystemToken ? $this->systemAccessToken : $this->accessToken);
        return $this->patchJson($uri, $data, $headers);
    }

    /**
     * Send an authorized JSON request for the DELETE method.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $useSystemToken
     * @return TestResponse
     */
    public function deleteJsonAuthorized(
        string $uri,
        array $data = [],
        array $headers = [],
        bool $useSystemToken = false
    ): TestResponse {
        $headers['Authorization'] = 'Bearer ' . ($useSystemToken ? $this->systemAccessToken : $this->accessToken);
        return $this->deleteJson($uri, $data, $headers);
    }
}
