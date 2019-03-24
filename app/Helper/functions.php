<?php

use App\Helper\WaybackMachine;
use App\Models\Link;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Shorthand for the current user settings
 *
 * @param string $key
 * @return mixed
 */
function usersettings(string $key = '')
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
 * Checks if cache tags can be used
 *
 * @return bool
 */
function useCacheTags(): bool
{
    return !in_array(strtolower(env('CACHE_DRIVER')), ['file', 'database']);
}

/**
 * Retrieve system settings
 *
 * @param string $key
 * @return mixed
 */
function systemsettings(string $key = '')
{
    if ($key === '') {
        return Setting::systemOnly()->get();
    }

    return Setting::systemOnly()->where('key', $key)->pluck('value')->first() ?: null;
}

/**
 * Output a correctly formatted date with the correct timezone
 *
 * @param \Carbon\Carbon $date
 * @param bool           $use_relational
 * @return string
 */
function formatDateTime(\Carbon\Carbon $date, bool $use_relational = false)
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
 * @return \Illuminate\Config\Repository|mixed
 */
function getPaginationLimit()
{
    $user_limit = usersettings('listitem_count');

    if ($user_limit) {
        return $user_limit;
    }

    return config('linkace.default.pagination');
}

/**
 * Generate all share links for a link, but for enabled services only
 *
 * @param \App\Models\Link $link
 * @return string
 */
function getShareLinks(\App\Models\Link $link)
{
    $cache_key = 'sharelinks_link_' . $link->id . (auth()->guest() ? '_guest' : '');
    $cache_duration = config('linkace.default.cache_duration');

    return Cache::remember($cache_key, $cache_duration, function () use ($link) {
        $services = config('sharing.services');
        $links = '';

        foreach ($services as $service => $details) {
            if (usersettings('share_' . $service) || auth()->guest()) {
                $links .= \App\Helper\Sharing::getShareLink($service, $link);
            }
        }

        return $links;
    });
}

/**
 * Get an SVG from a path and return it as a string
 *
 * @param      $path
 * @param null $width
 * @param null $height
 * @return mixed|string|string[]|null
 */
function displaySVG($path, $width = null, $height = null)
{
    $svg = file_get_contents($path);

    if ($width) {
        $svg = preg_replace('/width="([\d]+)"/i', "width='$width'", $svg);
    }

    if ($height) {
        $svg = preg_replace('/height="([\d]+)"/i', "height='$height'", $svg);
    }

    return $svg;
}

/**
 * Build sorting links for a table column
 *
 * @param string $label
 * @param string $route
 * @param string $type
 * @param string $order_by
 * @param string $order_dir
 * @return string
 */
function tableSorter($label, $route, $type, $order_by, $order_dir)
{
    $out = '<div class="d-flex">';
    $out .= '<span class="mr-1">' . e($label) . '</span>';
    $out .= '<span class="table-sorter ml-auto">';

    $order_url = $route . '?orderBy=' . $type . '&orderDir=';
    $order_icon = 'fa-sort';

    if ($type === $order_by) {

        if ($order_dir === 'asc') {
            $order_url .= 'desc';
            $order_icon = 'fa-sort-desc';
        } else {
            $order_url .= 'asc';
            $order_icon = 'fa-sort-asc';
        }

    } else {
        $order_url .= 'asc';
    }

    $out .= '<a href="' . $order_url . '"><i class="fa ' . $order_icon . '"></i></a>';
    $out .= '</span>';
    $out .= '</div>';

    return $out;
}

/**
 * Get the Wayback Machine link for an URL
 *
 * @param string|Link $link
 * @return null|string
 */
function waybackLink($link): ?string
{
    $link = $link->url ?? $link;

    return WaybackMachine::getArchiveLink($link);
}
