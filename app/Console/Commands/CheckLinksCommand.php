<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkCheckNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Sleep;
use Kovah\HtmlMeta\Exceptions\DisallowedIpException;
use Kovah\HtmlMeta\Facades\HtmlMeta as HtmlMetaFacade;

class CheckLinksCommand extends Command
{
    protected $signature = 'links:check {--limit=} {--noWait}';
    protected $description = 'This command checks the current status of a chunk of links. It is intended to be run on a schedule.';

    // Check a maximum of 20 links per user
    public int $limit = 20;

    protected array $movedLinks = [];

    protected array $brokenLinks = [];

    public function handle(): void
    {
        if ($this->option('limit')) {
            $this->limit = $this->option('limit');
        }

        User::query()->notBlocked()->get()->each(function (User $user) {
            $this->movedLinks = [];
            $this->brokenLinks = [];

            $links = $this->getLinks($user);

            foreach ($links as $link) {
                $this->checkLink($link);

                // Prevent spam-ish behaviour by throttling outgoing HTTP requests
                if ($this->option('noWait') === null) {
                    Sleep::for(1);
                }
            }

            $this->sendNotification($user);
        });
    }

    /**
     * Get links of the current users that
     * - don't have checks disabled
     * - were last checked more than 2 months ago or never checked
     * - begin with http, as other protocols are not supported
     *
     * @param User $user
     * @return Collection<Link>
     */
    protected function getLinks(User $user): Collection
    {
        return $user->links()
            ->where('check_disabled', false)
            ->where(function ($query) {
                $query->where('last_checked_at', '<', now()->subMonths(2))
                    ->orWhereNull('last_checked_at')
                    ->orWhere(function ($q) {
                        // Re-check broken links more frequently than the standard 2-month cycle
                        $q->where('status', Link::STATUS_BROKEN)
                            ->where('last_checked_at', '<', now()->subWeeks(
                                config('linkace.link_checks.broken_recheck_interval_weeks')
                            ));
                    });
            })
            ->where('url', 'LIKE', 'http%')
            ->oldest('id')
            ->limit($this->limit)
            ->get();
    }

    /**
     * Check the URL of a link and set the status accordingly.
     *
     * @param Link $link
     * @return void
     */
    protected function checkLink(Link $link): void
    {
        $this->output->write('Checking link ' . $link->url . ' ');

        try {
            $request = HtmlMetaFacade::applyPrivateIpProtection(setupHttpRequest(timeout: 20));
            $response = $request->head($link->url);
            $statusCode = $response->status();
        } catch (DisallowedIpException) {
            $this->line('› Skipped (private IP).');
            return;
        } catch (Exception $e) {
            // Set status code to null so the link will be marked as broken
            $statusCode = 999;
        }

        $link->last_checked_at = now();
        if ($statusCode >= 400) {
            $this->processBrokenLink($link);
        } elseif ($statusCode >= 300) {
            $this->processMovedLink($link);
        } else {
            $this->processWorkingLink($link);
        }
    }

    protected function processMovedLink(Link $link): void
    {
        $link->status = Link::STATUS_MOVED;
        $link->save();

        $this->warn('› Link moved to another URL!');
        $this->movedLinks[] = $link;
    }

    protected function processBrokenLink(Link $link): void
    {
        $link->status = Link::STATUS_BROKEN;
        $link->save();

        $this->error('› Link seems to be broken!');
        $this->brokenLinks[] = $link;
    }

    protected function processWorkingLink(Link $link): void
    {
        // If the Link has not the "ok" status yet, set it to ok
        if ($link->status !== Link::STATUS_OK) {
            $link->status = Link::STATUS_OK;
            $link->save();
        }

        $this->info('› Link looks okay.');
    }

    protected function sendNotification(User $user): void
    {
        if (empty($this->movedLinks) && empty($this->brokenLinks)) {
            // Do not send a notification if there are no errors
            return;
        }

        Notification::send(
            $user,
            new LinkCheckNotification($this->movedLinks, $this->brokenLinks)
        );

        $this->info('› Notification sent to the user.');
    }
}
