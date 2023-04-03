<?php

namespace App\Utils;

use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    /**
     * @param string $string
     * @param string $mode
     *
     * @return string
     */
    public static function convertKana(string $string, string $mode = 'asKV'): string
    {
        return mb_convert_kana($string, $mode);
    }
}
