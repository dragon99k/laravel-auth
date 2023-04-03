<?php

namespace App\Utils;

class Url
{
    /**
     * parse_urlのプロキシ
     *
     * @see https://www.php.net/manual/en/function.parse-url.php
     *
     * @param string $url
     *
     * @return array
     */
    public static function parseUrl($url)
    {
        return parse_url($url);
    }

    /**
     * URLからパスを抽出する
     *
     * @param string $url
     *
     * @return string
     */
    public static function extractPath($url)
    {
        $info = static::parseUrl($url);

        return $info['path'] ?? null;
    }
}
