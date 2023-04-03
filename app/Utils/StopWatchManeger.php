<?php

namespace App\Utils;

class StopWatchManeger
{
    /**
     * @var StopWatch[]
     */
    private static array $stopWatches = [];

    public static function getStopWatch(string $key): StopWatch
    {
        if (!isset(static::$stopWatches[$key])) {
            static::$stopWatches[$key] = new StopWatch();
        }

        return static::$stopWatches[$key];
    }

    /**
     * @return string
     */
    public static function getReport(): string
    {
        return implode("\n", Arr::map(
            static::$stopWatches,
            fn (StopWatch $stopWatch, string $key) => "{$key}={$stopWatch->getTime()}"
        ));
    }
}
