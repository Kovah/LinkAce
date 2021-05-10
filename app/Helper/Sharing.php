<?php

namespace App\Helper;

use App\Models\Link;

class Sharing
{
    public static $linkClasses = 'share-link btn btn-xs btn-outline-secondary';

    public static $placeholders = [
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
        $sharetext = trans(config('sharing.defaults.sharetext'));

        $sharetext = str_replace('#URL#', $link->url, $sharetext);

        return [
            $link->url, // URL
            self::encode($link->url), // endoced URL
            $subject, // subject
            self::encode($subject), // encoded subject
            $sharetext, // sharetext
            self::encode($sharetext), // encoded sharetext
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
