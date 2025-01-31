<?php

namespace Tests\Middleware;

use App\Http\Middleware\ContentTypeHeaderValidationMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContentTypeHeaderValidationMiddlewareTest extends TestCase
{
    public function testMissingContentTypeHeader(): void
    {
        $request = Request::create('/api/v1/links', 'POST');

        $middleware = new ContentTypeHeaderValidationMiddleware();

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals(415, $response->getStatusCode());
    }
}
