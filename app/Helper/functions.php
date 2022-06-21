<?php

use App\Helper\Sharing;
use App\Helper\WaybackMachine;
use App\Models\Link;
use App\Models\Setting;
use Carbon\CarbonInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Check if the setup was completed.
 *
 * @return bool
 */
function setupCompleted()
{
    try {
        return systemsettings('system_setup_completed');
    } catch (PDOException $e) {
        Log::error($e->getMessage());
        return false;
    }
}

/**
 * Shorthand for the current user settings
 *
 * @param string $key
 * @return mixed
 */
function usersettings(string $key = ''): mixed
{
    if (!auth()->user()) {
        return null;
    }

    if (!empty($key)) {
        return auth()->user()->settings()->get($key);
    }

    return auth()->user()->settings();
}

/**
 * Retrieve system settings
 *
 * @param string $key
 * @return Collection|null|string
 */
function systemsettings(string $key = '')
{
    $settings = Cache::rememberForever('systemsettings', fn() => Setting::systemOnly()->get()->pluck('value', 'key'));

    if ($key === '') {
        return $settings;
    }

    return $settings[$key] ?? null;
}

/**
 * Output a correctly formatted date with the correct timezone
 *
 * @param CarbonInterface $date
 * @param bool            $use_relational
 * @return string
 */
function formatDateTime(CarbonInterface $date, bool $use_relational = false): string
{
    $timezone = config('app.timezone');

    if ($use_relational) {
        return $date->setTimezone($timezone)->diffForHumans();
    }

    $format = config('linkace.default.date_format');
    $format .= ' ' . config('linkace.default.time_format');

    $user_date_format = usersettings('date_format');
    $user_time_format = usersettings('time_format');

    if ($user_date_format && $user_time_format) {
        $format = $user_date_format . ' ' . $user_time_format;
    }

    return $date->setTimezone($timezone)->format($format);
}

/**
 * Get the correct pagination limit
 *
 * @return mixed
 */
function getPaginationLimit(): mixed
{
    if (request()->has('per_page') && (int)request()->get('per_page') >= 0) {
        return (int)request()->get('per_page') > 0 ? (int)request()->get('per_page') : 999999999;
    }

    $default = config('linkace.default.pagination');

    if (request()->is('guest/*')) {
        return systemsettings('guest_listitem_count') ?: $default;
    }

    return usersettings('listitem_count') ?: $default;
}

/**
 * Generate all share links for a link, but for enabled services only
 *
 * @param Link $link
 * @return string
 */
function getShareLinks(Link $link): string
{
    $cacheKey = 'sharelinks_link_' . $link->id . (auth()->guest() ? '_guest' : '');
    $cacheDuration = config('linkace.default.cache_duration');

    return Cache::remember($cacheKey, $cacheDuration, function () use ($link) {
        $services = config('sharing.services');
        $links = '';

        foreach ($services as $service => $details) {
            if (request()->is('guest/*')) {
                if (systemsettings('guest_share_' . $service)) {
                    $links .= Sharing::getShareLink($service, $link);
                }
            } elseif (usersettings('share_' . $service)) {
                $links .= Sharing::getShareLink($service, $link);
            }
        }

        return $links;
    });
}

/**
 * Build sorting links for a table column
 */
function tableSorter(string $label, string $route, string $type, string $orderBy, string $orderDir): string
{
    $orderUrl = $route . '?orderBy=' . $type . '&orderDir=';
    $orderIcon = 'icon.sort';

    if ($type === $orderBy) {
        if ($orderDir === 'asc') {
            $orderUrl .= 'desc';
            $orderIcon = 'icon.sort-up';
        } else {
            $orderUrl .= 'asc';
            $orderIcon = 'icon.sort-down';
        }
    } else {
        $orderUrl .= 'asc';
    }

    return view('partials.table-sorter', [
        'label' => $label,
        'url' => $orderUrl,
        'icon' => $orderIcon,
    ]);
}

/**
 * Get the Wayback Machine link for a URL
 *
 * @param string|Link $link
 * @return null|string
 */
function waybackLink(string|Link $link): ?string
{
    $link = $link->url ?? $link;

    return WaybackMachine::getArchiveLink($link);
}

/**
 * Return proper link attributes based on the links_new_tab user setting
 *
 * @return string
 */
function linkTarget(): string
{
    $newTab = 'target="_blank" rel="noopener noreferrer"';

    if (request()->is('guest/*')) {
        return systemsettings('guest_links_new_tab') ? $newTab : '';
    }

    return usersettings('links_new_tab') ? $newTab : '';
}

/**
 * Properly escape symbols used in search queries.
 *
 * @param string $query
 * @return string
 */
function escapeSearchQuery(string $query): string
{
    return str_replace(
        ['\\', '%', '_', '*'],
        ['\\\\', '\\%', '\\_', '\\*'],
        $query
    );
}

/**
 * Set up an HTTP request with a random user agent
 *
 * @param int $timeout
 * @return PendingRequest
 */
function setupHttpRequest(int $timeout = 10): PendingRequest
{
    $request = Http::timeout($timeout);
    if (config('html-meta.user_agents', false)) {
        $agents = config('html-meta.user_agents');
        $request->withHeaders(['User-Agent' => $agents[array_rand($agents)]]);
    }

    return $request;
}

/**
 * Generates the bookmarklet code for the user settings page
 *
 * @return string
 */
function bookmarkletUrl(): string
{
    $bmCode = 'javascript:javascript:(function(){var%20url%20=%20location.href;' .
        "var%20description=document.getSelection()||'';" .
        "var%20title%20=%20document.title%20||%20url;window.open('##URL##?u='%20+%20encodeURIComponent(url)" .
        "+'&t='%20+%20encodeURIComponent(title)+'&d='+encodeURIComponent(description)," .
        "'_blank','menubar=no,height=720,width=600,toolbar=no," .
        "scrollbars=yes,status=no,dialog=1');})();";

    return str_replace('##URL##', route('bookmarklet-add'), $bmCode);
}
