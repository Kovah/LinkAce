<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/*
 * Because this event is dispatched after the link is deleted, only the ID is available
 * rather than the entire Link model (as found in LinkCreated and LinkUpdated)
 */
class LinkDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public int $link_id)
    {
    }
}
