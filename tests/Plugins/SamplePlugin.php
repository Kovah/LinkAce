<?php

namespace Tests\Plugins;

use App\Events\LinkCreated;
use App\Events\LinkUpdated;

class SamplePlugin
{
    public function handle(LinkCreated|LinkUpdated $event) : void
    {
        // do nothing
    }
}
