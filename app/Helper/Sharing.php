<?php

namespace App\Helper;

use App\Models\Link;

/**
 * Class Sharing
 *
 * @package App\Helper
 */
class Sharing
{
    public static $link_classes = 'share-link btn btn-xs btn-outline-primary';

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
        $service_details = config('sharing.services.' . $service);
        $service_name = trans('sharing.service.' . $service);
        $share_action = $service_details['action'];
        $link_data = self::generateLinkData($link);

        $share_action = str_replace(self::$placeholders, $link_data, $share_action);

        $share_link = '<a class="' . self::$link_classes . '"';
        $share_link .= ' href="' . $share_action . '"';
        $share_link .= ' title="' . trans('sharing.share', ['service' => $service_name]) . '">';
        $share_link .= '<i class="fa-fw ' . $service_details['icon'] . '"></i>';
        $share_link .= '</a>';

        return $share_link;
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
     * @param $string
     * @return string
     */
    protected static function encode($string)
    {
        return rawurlencode($string);
    }
}
