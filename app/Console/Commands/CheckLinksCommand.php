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
                    ->orWhereNull('last_checked_at');
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

        if (config('html-meta.block_private_ips', true) && $this->pointsToPrivateIp($link->url)) {
            $this->line('› Skipped (private IP).');
            return;
        }

        try {
            $request = setupHttpRequest(timeout: 20);
            $response = $request->head($link->url);
            $statusCode = $response->status();
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

    protected function pointsToPrivateIp(string $url): bool
    {
        $host = trim(parse_url($url, PHP_URL_HOST) ?? '', '[]');

        if ($host === '') {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return !$this->isPublicIp($host);
        }

        foreach ($this->resolveHostIps($host) as $ip) {
            if (!$this->isPublicIp($ip)) {
                return true;
            }
        }

        return false;
    }

    protected function isPublicIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }

    /**
     * @return array<int, string>
     */
    protected function resolveHostIps(string $host): array
    {
        $ips = [];
        $records = @dns_get_record($host, DNS_A + DNS_AAAA);

        if (is_array($records)) {
            foreach ($records as $record) {
                if (isset($record['ip'])) {
                    $ips[] = $record['ip'];
                }
                if (isset($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        }

        if (empty($ips)) {
            $ipv4Addresses = @gethostbynamel($host);
            if (is_array($ipv4Addresses)) {
                $ips = $ipv4Addresses;
            }
        }

        return array_values(array_unique($ips));
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
