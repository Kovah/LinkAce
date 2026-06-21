<?php

namespace Tests\Mocks;

use App\Events\LinkCreated;

class TooManyParametersPlugin
{
    public function handle(LinkCreated $event, string $somethingElse)
    {
    }
}
