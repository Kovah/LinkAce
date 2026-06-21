<?php

namespace Tests\Mocks;

use App\Events\LinkCreated;

class SimplePlugin
{
    public function handle(LinkCreated $event)
    {
    }
}
