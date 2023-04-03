<?php

namespace App\Utils;

use Illuminate\Support\Facades\Lang as BaseLang;

class Lang extends BaseLang
{
    /**
     * @param string $key
     *
     * @return string
     */
    public static function json(string $key)
    {
        return json(static::get($key));
    }
}
