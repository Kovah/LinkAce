<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkCheckNotification;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

/**
 * Class CheckLinksCommand
 *
 * @package App\Console\Commands
 */
class CheckLinksCommand extends Command
{
    protected $signature = 'links:check';

    /** @var int $limit Check a maximum of 100 links at once */
    protected $limit = 100;

    /** @var int */
    protected $offset;

    /** @var int */
    protected $total;

    /** @var int */
    protected $checked_link_count;

    /** @var Client */
    protected $client;

    /** @var string */
    protected $cache_key_offset = 'command_links:check_offset';

    /** @var string */
    protected $cache_key_skip_timestamp = 'command_links:check_skip_timestamp';

    /** @var string */
    protected $cache_key_checked_count = 'command_links:check_checked_count';

    /** @var array */
    protected $moved_links = [];

    /** @var array */
    protected $broken_links = [];

    /**
     * RegisterUser constructor.
     */
    public function __construct()
    {
        $this->client = new Client();

        parent::__construct();
    }

    public function handle(): void
    {
        // Check if the command should skip the execution
        $skip_timestamp = Cache::get($this->cache_key_skip_timestamp);
        $this->offset = Cache::get($this->cache_key_offset, 0);
        $this->checked_link_count = Cache::get($this->cache_key_checked_count, 0);

        if (now()->timestamp < $skip_timestamp) {
            return;
        }

        $links = $this->getLinks();

        // Cancel if there are no links to check
        if ($links->isEmpty()) {
            Cache::forget($this->cache_key_offset);
            Cache::forget($this->cache_key_skip_timestamp);

            $this->comment('No links found, aborting...');
            return;
        }

        // Check all provided links
        $this->comment('Checking ' . $links->count() . ' links now.');

        $links->each(function ($link) {
            $this->checkLink($link);

            // Prevent spam-ish behaviour by limiting outgoing HTTP requests
            sleep(1);
        });

        // Send notification about moved/broken links
        $this->sendNotification();

        // Check if there are more links to check
        $checked_count = $this->checked_link_count + $links->count();
        Cache::forever($this->cache_key_checked_count, $checked_count);

        if ($this->total > $checked_count) {
            // If yes, simply save the offset to the cache.
            // The next link check will pick it up and continue the check.
            $next_offset = $this->offset + $this->limit;
            Cache::forever($this->cache_key_offset, $next_offset);

            $this->comment('Saving offset for next link check.');
        } else {
            // If not, all links have been successfully checked.
            // Save a cache flag that prevents link checks for the next days.
            $next_check = now()->addDays(5)->timestamp;
            Cache::forever($this->cache_key_skip_timestamp, $next_check);

            $this->comment(
                'All existing links checked, next link check scheduled for ' . now()->addDays(5)->toDateTimeString()
            );
        }
    }

    /**
     * Get links but limit the results to a fixed number of links.
     * If there is an offset saved, use this instead of beginning from the first entry.
     *
     * @return mixed
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

        $options = [
            'http_errors' => false, // Do not throw exceptions for 4xx and 5xx errors
            'timeout' => 5, // wait a maximum of 5 seconds for the request to finish
        ];

        try {
            $res = $this->client->request('GET', $link->url, $options);
            $status_code = $res->getStatusCode();
        } catch (\Exception $e) {
            // Set status code to null so the link will be marked as broken
            $status_code = 0;
        }

        if ($status_code !== 200) {
            $this->processBrokenLink($status_code, $link);
        } else {
            $this->processWorkingLink($link);
        }
    }

    /**
     * Set the Link status to either moved or broken depending on the given
     * status code.
     *
     * @param int  $status_code
     * @param Link $link
     */
    protected function processBrokenLink(int $status_code, Link $link): void
    {
        if ($status_code === 301 || $status_code === 302) {
            $link->status = Link::STATUS_MOVED;
            $this->warn('› Link moved to another URL!');

            $this->moved_links[] = $link;
        } else {
            $link->status = Link::STATUS_BROKEN;
            $this->error('› Link seems to be broken!');

            $this->broken_links[] = $link;
        }

        $link->save();
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
    protected function sendNotification()
    {
        if (empty($this->moved_links) && empty($this->broken_links)) {
            // Do not send a notification if there are no errors
            return;
        }

        $this->info('› Notification sent to the user.');

        Notification::send(
            User::find(1),
            new LinkCheckNotification($this->moved_links, $this->broken_links)
        );
    }
}
