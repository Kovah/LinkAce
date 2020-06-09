<?php

namespace Tests\Controller\API;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        Queue::fake();

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml, 200),
        ]);
    }

    protected function generateHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->user->api_token,
        ];
    }
}
