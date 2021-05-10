<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkCheckNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class CheckLinksCommand extends Command
{
    protected $signature = 'links:check {--limit=} {--noWait}';
    protected $description = 'This command checks the current status of a chunk of links. It is intended to be run on a schedule.';

    /** @var int $limit Check a maximum of 100 links at once */
    public $limit = 100;

    /** @var int */
    protected $offset;

    /** @var int */
    protected $total;

    /** @var int */
    protected $checkedLinkCount;

    /** @var string */
    protected $cacheKeyOffset = 'command_links:check_offset';

    /** @var string */
    protected $cacheKeySkipTimestamp = 'command_links:check_skip_timestamp';

    /** @var string */
    protected $cacheKeyCheckedCount = 'command_links:check_checked_count';

    /** @var array */
    protected $movedLinks = [];

    /** @var array */
    protected $brokenLinks = [];

    /** @var array */
    protected $validUrlSchemes = ['http', 'https'];

    public function handle(): void
    {
        // Check if the command should skip the execution
        $skipTimestamp = Cache::get($this->cacheKeySkipTimestamp);
        $this->offset = Cache::get($this->cacheKeyOffset, 0);
        $this->checkedLinkCount = Cache::get($this->cacheKeyCheckedCount, 0);

        if (now()->timestamp < $skipTimestamp) {
            return;
        }

        if ($this->option('limit')) {
            $this->limit = $this->option('limit');
        }

        $links = $this->getLinks();

        // Cancel if there are no links to check
        if ($links->isEmpty()) {
            Cache::forget($this->cacheKeyOffset);
            Cache::forget($this->cacheKeySkipTimestamp);

            $this->comment('No links found, aborting...');
            return;
        }

        // Check all provided links
        $this->comment('Checking ' . $links->count() . ' links now.');

        $links->each(function ($link) {
            $this->checkLink($link);

            // Prevent spam-ish behaviour by throttling outgoing HTTP requests
            if ($this->option('noWait') === null) {
                sleep(1);
            }
        });

        $this->sendNotification();

        $checkedCount = $this->checkedLinkCount + $links->count();
        Cache::forever($this->cacheKeyCheckedCount, $checkedCount);

        if ($this->total > $checkedCount) {
            // If yes, simply save the offset to the cache.
            // The next link check will pick it up and continue the check.
            $nextOffset = $this->offset + $this->limit;
            Cache::forever($this->cacheKeyOffset, $nextOffset);

            $this->comment('Saving offset for next link check.');
        } else {
            // If not, all links have been successfully checked.
            // Save a cache flag that prevents link checks for the next days.
            $nextCheck = now()->addDays(20)->timestamp;
            Cache::forever($this->cacheKeySkipTimestamp, $nextCheck);

            $this->comment(
                'All existing links checked, next link check scheduled for ' . now()->addDays(5)->toDateTimeString()
            );
        }
    }

    /**
     * Get links but limit the results to a fixed number of links.
     * If there is an offset saved, use this instead of beginning from the first entry.
     *
     * @return \LaravelIdea\Helper\App\Models\_IH_Link_C|Link[]
     */
    protected function getLinks()
    {
        // Get the total amount of remaining links
        $this->total = Link::count();

        // Get a portion of the remaining links based on the limit
        return Link::where('check_disabled', false)
            ->orderBy('id', 'ASC')
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();
    }

    /**
     * Check the URL of an link and set the status accordingly.
     *
     * @param Link $link
     * @return void
     */
    protected function checkLink(Link $link): void
    {
        $this->output->write('Checking link ' . $link->url . ' ');

        $urlScheme = parse_url($link->url, PHP_URL_SCHEME);
        if (in_array($urlScheme, $this->validUrlSchemes) === false) {
            $this->warn('› Invalid scheme [' . $urlScheme . '], skipping Link.');
            return;
        }

        try {
            $response = Http::timeout(20)->head($link->url);
            $statusCode = $response->status();
        } catch (Exception $e) {
            // Set status code to null so the link will be marked as broken
            $statusCode = 0;
        }

        if ($statusCode >= 400) {
            $this->processBrokenLink($link);
        } elseif ($statusCode >= 300) {
            $this->processMovedLink($link);
        } else {
            $this->processWorkingLink($link);
        }
    }

    /**
     * Set the Link status to either moved or broken depending on the given
     * status code.
     *
     * @param Link $link
     */
    protected function processMovedLink(Link $link): void
    {
        $link->status = Link::STATUS_MOVED;
        $link->save();

        $this->warn('› Link moved to another URL!');
        $this->movedLinks[] = $link;
    }

    /**
     * Set the Link status to either moved or broken depending on the given
     * status code.
     *
     * @param Link $link
     */
    protected function processBrokenLink(Link $link): void
    {
        $link->status = Link::STATUS_BROKEN;
        $link->save();

        $this->error('› Link seems to be broken!');
        $this->brokenLinks[] = $link;
    }

    /**
     * If the Link has not the "ok" status, set it to ok.
     *
     * @param Link $link
     */
    protected function processWorkingLink(Link $link): void
    {
        if ($link->status !== Link::STATUS_OK) {
            $link->status = Link::STATUS_OK;
            $link->save();
        }

        $this->info('› Link looks okay.');
    }

    /**
     * Send notification to the main user if not running from the console.
     *
     * @return void
     */
    protected function sendNotification(): void
    {
        if (empty($this->movedLinks) && empty($this->brokenLinks)) {
            // Do not send a notification if there are no errors
            return;
        }

        Notification::send(
            User::find(1),
            new LinkCheckNotification($this->movedLinks, $this->brokenLinks)
        );

        $this->info('› Notification sent to the user.');
    }
}
