<?php

namespace Tests\Plugins;

use App\Events\LinkUpdated;

class SamplePlugin
{
    public static array $events = [LinkUpdated::class];

    public function handle(LinkUpdated $event) : void
    {
        // do nothing
    }
}
