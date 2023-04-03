<?php

namespace App\Utils;

interface ExclusiveLock
{
    public const KEY_COLLECT_UNKOWN_TWEETS = 'collect_unknown_tweets:%s';

    /**
     * @param string $key
     *
     * @return void
     */
    public static function lock(string $key);

    /**
     * @param string $key
     *
     * @return void
     */
    public static function unlock(string $key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function locked(string $key): bool;
}
