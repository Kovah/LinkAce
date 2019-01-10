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
