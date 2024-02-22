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

    protected static function generateLinkData(Link $link): array
    {
        $subject = $link->title ?: trans(config('sharing.defaults.subject'));
        $shareText = trans(config('sharing.defaults.sharetext'));

        $shareText = str_replace('#URL#', $link->url, $shareText);

        return [
            $link->url, // URL
            self::encode($link->url), // encoded URL
            $subject, // subject
            self::encode($subject), // encoded subject
            $shareText, // share text
            self::encode($shareText), // encoded share text
        ];
    }

    // Encode a string with the basic rawurlencode function
    // "Hello this is text!" becomes Hello%20this%20is%20text%21%
    protected static function encode(string $string): string
    {
        return rawurlencode($string);
    }
}
