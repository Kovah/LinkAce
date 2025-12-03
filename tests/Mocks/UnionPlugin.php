<?php

namespace Tests\Mocks;

use App\Events\LinkCreated;
use App\Events\LinkUpdated;

class UnionPlugin
{
    public function handle(LinkCreated|LinkUpdated $event)
    {
    }
}
