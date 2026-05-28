<?php

namespace Tests\Middleware;

use App\Enums\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentTypeHeaderValidationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function testMissingContentTypeHeader(): void
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('api-test', [ApiToken::ABILITY_USER_ACCESS])->plainTextToken;

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml),
        ]);

        // content-type and accept are missing
        $this->post('api/v2/links', ['url' => 'https://example.com'], ['Authorization' => 'Bearer ' . $accessToken])
            ->assertUnsupportedMediaType()
            ->assertJson([
                'error' => 'Invalid Content-Type header, LinkAce only supports JSON input',
            ]);

        // content-type is present, but not supported
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/xml',
        ])
            ->assertUnsupportedMediaType()
            ->assertJson([
                'error' => 'Invalid Content-Type header, LinkAce only supports JSON input',
            ]);

        // accept header is missing
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])
            ->assertUnsupportedMediaType()
            ->assertJson([
                'error' => 'Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // accept header is present, but not supported
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/xml',
        ])
            ->assertUnsupportedMediaType()
            ->assertJson([
                'error' => 'Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->assertOk();

        // request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ])->assertOk();

        // request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json;q=0.8',
        ])->assertOk();

        // request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json;q=0.8, application/xml;q=0.2',
        ])->assertOk();
    }
}
