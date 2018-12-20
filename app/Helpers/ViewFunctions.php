<?php


    /**
     * Used in views.
     * Accepts a string that should be a URL, appends http:// to it if doesn't have a protocol.
     *
     * @var {string} url to display
     *
     * @return {string}
     */
    function add_protocol($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
             $url = "http://" . $url;
         }
         return $url;
    }