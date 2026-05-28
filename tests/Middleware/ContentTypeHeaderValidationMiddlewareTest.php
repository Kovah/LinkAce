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

        // ❌ 1. content-type and accept are missing → 406
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '1. Invalid Accept header and Content-Type header, LinkAce only supports JSON input',
            ]);

        // ❌ 2. content-type is present, but not supported; accept header is missing → 406
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/xml',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '2. Invalid Accept header, LinkAce only supports JSON input',
            ]);

        // ❌ 3. accept header is missing → 406
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '3. Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // ❌ 4. accept header is present, but not supported → 406
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/xml',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '4. Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // ❌ 5. accept header is present, but content-type not supported → 415
        $this->post('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/xml',
            'Accept' => 'application/json',
        ])
            ->assertUnsupportedMediaType()
            ->assertJson([
                'error' => '5. Invalid Content-Type header, LinkAce only supports JSON input',
            ]);

        // ✅ 6. request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->assertOk();

        // ✅ 7. request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ])->assertOk();

        // ✅ 8. request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json;q=0.8',
        ])->assertOk();

        // ✅ 9. request headers are correct
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json;q=0.8, application/xml;q=0.2',
        ])->assertOk();

        // ❌ 10. 1ccept wildcard */* → 406
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '10. Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // ❌ 11. accept wildcard application/* → 406
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/*',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '11. Invalid Accept header, LinkAce only supports JSON output',
            ]);

        // ❌ 12. accept header is present, but not supported → 406
        $this->postJson('api/v2/links', ['url' => 'https://example.com'], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain, application/xml',
        ])
            ->assertNotAcceptable()
            ->assertJson([
                'error' => '12. Invalid Accept header, LinkAce only supports JSON output',
            ]);
    }
}
