<?php

namespace App\Helper;

use Composer\Semver\Comparator;
use Illuminate\Support\Facades\Http;

/**
 * Class UpdateHelper
 *
 * @package App\Helper
 */
class UpdateHelper
{
    protected static $releaseApiUrl = 'https://api.github.com/repos/kovah/linkace/releases';

    /**
     * Returns the version string if there is a newer version is available.
     * Returns true if the check was successful, but no updates was found.
     * Returns false if the check could not be executed, e.g. due to network
     * issues.
     *
     * @return bool|string
     */
    public static function checkForUpdates()
    {
        $currentVersion = config('linkace.version');
        $latestVersion = self::getCurrentVersionFromAPI();

        if ($latestVersion === null) {
            return false;
        }

        if (Comparator::greaterThan($latestVersion, $currentVersion)) {
            return $latestVersion;
        }

        return true;
    }

    /**
     * We try to get the latest releases from the Github API and then get the
     * tag name from the latest. If the request failed for some reason or no
     * release could be found, null is returned.
     *
     * @return string|null
     */
    protected static function getCurrentVersionFromAPI(): ?string
    {
        $response = Http::get(self::$releaseApiUrl);

        if (!$response->successful()) {
            return null;
        }

        $releases = $response->json();

        return $releases[0]['tag_name'] ?? null;
    }
}
