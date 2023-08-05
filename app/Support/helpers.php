<?php

if (! function_exists('parse_url_query_string')) {
    /**
     * Extract the URL query string as an associative array.
     *
     * @param string $url
     *
     * @return array
     */
    function parse_url_query_string(string $url): array
    {
        $output = [];
        $urlData = parse_url($url, PHP_URL_QUERY);

        if ($urlData) {
            parse_str($urlData, $output);
        }

        return $output;
    }
}