<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaybackMachine
{
    public static string $baseUrl = 'https://web.archive.org';

    /**
     * Save an URL to the Wayback Machine
     *
     * @param string $url
     * @return bool
     */
    public static function saveToArchive(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Abort if provided string is not a URL
            return false;
        }

        $archiveUrl = self::$baseUrl . '/save/' . $url;

        $request = Http::timeout(10);
        if (config('html-meta.user_agents', false)) {
            $agents = config('html-meta.user_agents');
            $request->withHeaders(['User-Agent' => $agents[array_rand($agents)]]);
        }
        $response = $request->head($archiveUrl);

        try {
            $response->throw();
        } catch (\Exception $e) {
            Log::warning($archiveUrl . ': ' . $e->getMessage());
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
            // Abort if provided string is not a URL
            return null;
        }

        return self::$baseUrl . '/web/*/' . $url;
    }
}
