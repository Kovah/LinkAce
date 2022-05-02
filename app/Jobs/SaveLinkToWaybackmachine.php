<?php

namespace App\Jobs;

use App\Helper\WaybackMachine;
use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveLinkToWaybackmachine implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param Link $link
     */
    public function __construct(protected Link $link)
    {
    }

    /**
     * Notify the Wayback Machine about the link
     *
     * @return void
     */
    public function handle(): void
    {
        WaybackMachine::saveToArchive($this->link->url);
    }
}
