<?php

namespace App\Plugins;

use App\Enums\ModelAttribute;
use App\Events\LinkCreated;
use App\Jobs\SaveLinkToWaybackmachine;

class NewLinkToWaybackMachine
{
    public static array $events = [LinkCreated::class];
    /**
     * Dispatch the SaveLinkToWaybackmachine job, if Internet Archive backups
     * are enabled.
     * If the link is private, private Internet Archive backups must be enabled
     * too.
     */
    public function handle(LinkCreated $event) : void
    {
        if (usersettings('archive_backups_enabled') === false) {
            return;
        }

        if ($event->link->visibility === ModelAttribute::VISIBILITY_PRIVATE
            && usersettings('archive_private_backups_enabled') === false
        ) {
            return;
        }

        SaveLinkToWaybackmachine::dispatchAfterResponse($event->link);
    }
}
