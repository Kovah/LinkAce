<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public int $link_id)
    {
        // no op constructor
    }
}
