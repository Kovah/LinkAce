<?php

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
    $services = config('sharing.services');
    $links = '';

    foreach ($services as $service => $details) {
        if (usersettings('share_' . $service)) {
            $links .= \App\Helper\Sharing::getShareLink($service, $link);
        }
    }

    return $links;
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
