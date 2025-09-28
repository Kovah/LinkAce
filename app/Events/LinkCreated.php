<?php

namespace App\Events;

use App\Models\Link;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Link $link)
    {
        // no op constructor
    }
}
