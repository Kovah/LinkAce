<?php

namespace Tests\Middleware;

use App\Http\Middleware\ContentLengthHeaderValidationMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContentLengthHeaderValidationMiddlewareTest extends TestCase
{
    public function testMissingContentTypeHeader(): void
    {
        $request = Request::create('/api/v1/links', 'POST');

        $middleware = new ContentLengthHeaderValidationMiddleware();

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals(411, $response->getStatusCode());
    }
}
