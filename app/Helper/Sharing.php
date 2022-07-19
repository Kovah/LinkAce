<?php

namespace App\Helper;

use App\Models\Link;

class Sharing
{
    public static string $linkClasses = 'share-link btn btn-sm btn-light';

    public static array $placeholders = [
        '#URL#',
        '#E-URL#',
        '#SUBJECT#',
        '#E-SUBJECT#',
        '#SHARETEXT#',
        '#E-SHARETEXT#',
    ];

    /**
     * @param string $service
     * @param Link   $link
     * @return string
     */
    public static function getShareLink(string $service, Link $link): string
    {
        $serviceDetails = config('sharing.services.' . $service);
        $serviceName = trans('sharing.service.' . $service);
        $shareAction = $serviceDetails['action'];
        $linkData = self::generateLinkData($link);

        $shareAction = str_replace(self::$placeholders, $linkData, $shareAction);

        return view('models.links.partials.share-link', [
            'class' => self::$linkClasses,
            'href' => $shareAction,
            'title' => trans('sharing.share', ['service' => $serviceName]),
            'icon' => $serviceDetails['icon'],
        ]);
    }

    /**
     * Prepare all needed raw or encoded values for the share link
     *
     * @param Link $link
     * @return array
     */
    protected static function generateLinkData(Link $link): array
    {
        $subject = $link->title ?: trans(config('sharing.defaults.subject'));
        $shareText = trans(config('sharing.defaults.sharetext'));

        $shareText = str_replace('#URL#', $link->url, $shareText);

        return [
            $link->url, // URL
            self::encode($link->url), // endoced URL
            $subject, // subject
            self::encode($subject), // encoded subject
            $shareText, // sharetext
            self::encode($shareText), // encoded sharetext
        ];
    }

    /**
     * Encode a string with the basic rawurlencode function
     * "Hello this is text!" becomes Hello%20this%20is%20text%21%
     *
     * @param string $string
     * @return string
     */
    protected static function encode(string $string): string
    {
        return rawurlencode($string);
    }
}
