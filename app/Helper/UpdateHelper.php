<?php

namespace App\Helper;

use Composer\Semver\Comparator;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateHelper
{
    protected const RELEASE_API_URL = 'https://updates.linkace.org/api/current-version';

    /**
     * Get the current version from the package.json file and cache it for a day.
     *
     * @return mixed
     */
    public static function currentVersion()
    {
        return Cache::remember('current-version', 86400, function () {
            try {
                $package = json_decode(Storage::disk('root')->get('package.json'), false);
            } catch (Exception $e) {
                return null;
            }

            return isset($package->version) ? 'v' . $package->version : null;
        });
    }

    /**
     * Returns the version string if there is a newer version is available.
     * Returns true if the check was successful, but no updates was found.
     * Returns false if the check could not be executed, e.g. due to network
     * issues.
     *
     * @param bool $cacheResult
     * @return bool|string
     */
    public static function checkForUpdates(bool $cacheResult = false)
    {
        return Cache::remember('updatecheck', $cacheResult ? 86400 : 0, function () {
            $currentVersion = self::currentVersion();
            $latestVersion = self::getCurrentVersionFromAPI();

            if ($latestVersion === null) {
                return false;
            }

            if (Comparator::greaterThan($latestVersion, $currentVersion)) {
                return $latestVersion;
            }

            return true;
        });
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
        $response = Http::get(self::RELEASE_API_URL);

        return $response->successful() ? $response->body() : null;
    }
}
