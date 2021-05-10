<?php

namespace App\Helper;

class LinkAce
{
    /**
     * Generate the code for the bookmarklet
     */
    public static function generateBookmarkletCode(): string
    {
        $bmCode = 'javascript:javascript:(function(){var%20url%20=%20location.href;' .
            "var%20description=document.getSelection()||'';" .
            "var%20title%20=%20document.title%20||%20url;window.open('##URL##?u='%20+%20encodeURIComponent(url)" .
            "+'&t='%20+%20encodeURIComponent(title)+'&d='+encodeURIComponent(description)," .
            "'_blank','menubar=no,height=720,width=600,toolbar=no," .
            "scrollbars=yes,status=no,dialog=1');})();";

        return str_replace('##URL##', route('bookmarklet-add'), $bmCode);
    }
}
