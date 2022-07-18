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
            // Abort if provided string is not an URL
            return false;
        }

        $archiveUrl = self::$baseUrl . '/save/' . $url;

        $request = Http::timeout(30);
        if (config('html-meta.user_agents', false)) {
            $agents = config('html-meta.user_agents');
            $request->withHeaders(['User-Agent' => $agents[array_rand($agents)]]);
        }
        try {
            $response = $request->head($archiveUrl);
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'cURL error 28: Operation timed out')) {
                Log::warning($archiveUrl . ': ' . $e->getMessage());
            }
            return false;
        }

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
            // Abort if provided string is not an URL
            return null;
        }

        return self::$baseUrl . '/web/*/' . $url;
    }
}
