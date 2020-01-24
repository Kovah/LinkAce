<?php

namespace App\Helper;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class WaybackMachine
 *
 * @package App\Helper
 */
class WaybackMachine
{
    /** @var string */
    public static $base_url = 'https://web.archive.org';

    /**
     * Save an URL to the Wayback Machine
     *
     * @param string $url
     * @return bool
     */
    public static function saveToArchive(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Abort if provided string is not an URL
            return false;
        }

        $archive_url = self::$base_url . '/save/' . $url;

        try {
            $client = new Client();
            $client->request('GET', $archive_url, [
                'http_errors' => false,
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::warning($e);
            return false;
        }

        return true;
    }

    /**
     * Get the link to the Wayback Machine archive for a specific URL
     *
     * @param string $url
     * @return null|string
     */
    public static function getArchiveLink(string $url): ?string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Abort if provided string is not an URL
            return null;
        }

        return self::$base_url . '/web/*/' . $url;
    }
}
