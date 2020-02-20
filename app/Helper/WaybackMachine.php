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
    public static $baseUrl = 'https://web.archive.org';

    /** @var array */
    protected $clientConfig;

    public function __construct(array $clientConfig = [])
    {
        $this->clientConfig = $clientConfig;
    }

    /**
     * Save an URL to the Wayback Machine
     *
     * @param string $url
     * @return bool
     */
    public function saveToArchive(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Abort if provided string is not an URL
            return false;
        }

        $archiveUrl = self::$baseUrl . '/save/' . $url;

        try {
            $client = new Client($this->clientConfig);
            $client->request('GET', $archiveUrl, [
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

        return self::$baseUrl . '/web/*/' . $url;
    }
}
