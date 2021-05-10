<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use Kovah\HtmlMeta\Exceptions\InvalidUrlException;
use Kovah\HtmlMeta\Exceptions\UnreachableUrlException;

class HtmlMeta
{
    /** @var string */
    protected $url;

    /** @var array */
    protected $fallback;

    /** @var array */
    protected $meta;

    /**
     * Get the title and description of an URL.
     *
     * Returned array:
     * array [
     *   'success' => bool,
     *   'title' => string,
     *   'description' => string|null,
     * ]
     *
     * @param string $url
     * @param bool   $flashAlerts
     * @return array
     */
    public function getFromUrl(string $url, bool $flashAlerts = false): array
    {
        $this->url = $url;
        $this->buildFallback();

        try {
            $this->meta = \Kovah\HtmlMeta\Facades\HtmlMeta::forUrl($url)->getMeta();
        } catch (InvalidUrlException $e) {
            Log::warning($url . ': ' . $e->getMessage());
            if ($flashAlerts) {
                flash(trans('link.added_connection_error'), 'warning');
            }
            return $this->fallback;
        } catch (UnreachableUrlException $e) {
            Log::warning($url . ': ' . $e->getMessage());
            if ($flashAlerts) {
                flash(trans('link.added_request_error'), 'warning');
            }
            return $this->fallback;
        }

        return $this->buildLinkMeta();
    }

    /**
     * Build a response array containing the link meta including a success flag.
     *
     * @return array
     */
    protected function buildLinkMeta(): array
    {
        $this->meta['description'] = $this->meta['description']
            ?? $this->meta['og:description']
            ?? $this->meta['twitter:description']
            ?? null;

        return [
            'success' => true,
            'title' => $this->meta['title'] ?? $this->fallback['title'],
            'description' => $this->meta['description'],
            'thumbnail' => $this->getThumbnail(),
        ];
    }

    /**
     * The fallback is used in case of errors while trying to get the link meta.
     */
    protected function buildFallback(): void
    {
        $this->fallback = [
            'success' => false,
            'title' => parse_url($this->url, PHP_URL_HOST) ?? $this->url,
            'description' => false,
            'thumbnail' => null,
        ];
    }

    /**
     * Try to get the thumbnail from the meta tags and handle specific cases where we know how to get a proper image
     * from the website.
     *
     * @return string|null
     */
    protected function getThumbnail(): ?string
    {
        $thumbnail = $this->meta['og:image']
            ?? $this->meta['twitter:image']
            ?? null;

        if (!is_null($thumbnail) && parse_url($thumbnail, PHP_URL_HOST) === null) {
            // If the thumbnail does not contain the domain, add it in front of it
            $urlInfo = parse_url($this->url);
            $baseUrl = sprintf('%s://%s/', $urlInfo['scheme'], $urlInfo['host']);
            $thumbnail = $baseUrl . trim($thumbnail, '/');
        }

        /*
         * Edge case of Youtube only (because of Youtube EU cookie consent)
         * Formula based on https://stackoverflow.com/a/2068371, returns Youtube image url
         * https://img.youtube.com/vi/[video-id]/mqdefault.jpg
         */
        if (is_null($thumbnail)) {
            if (str_contains($this->url, 'youtube.com') && str_contains($this->url, 'v=')) {
                preg_match('/v=([a-zA-Z0-9_]+)/', $this->url, $matched);
                $thumbnail = isset($matched[1]) ? 'https://img.youtube.com/vi/' . $matched[1] . '/mqdefault.jpg' : null;
            }

            if (str_contains($this->url, 'youtu.be')) {
                preg_match('/youtu.be\/([a-zA-Z0-9_]+)/', $this->url, $matched);
                $thumbnail = isset($matched[1]) ? 'https://img.youtube.com/vi/' . $matched[1] . '/mqdefault.jpg' : null;
            }
        }

        return $thumbnail;
    }
}
