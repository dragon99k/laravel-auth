<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

class ExclusiveLockConcrete implements ExclusiveLock
{
    /**
     * @param string $key
     *
     * @return void
     */
    public static function lock(string $key)
    {
        $dir = static::getDir();

        $disk = static::getDisk();

        $disk->makeDirectory($dir);

        $disk->put(static::getPath($dir, $key), '1');
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public static function unlock(string $key)
    {
        $dir = static::getDir();

        $disk = static::getDisk();

        if ($disk->exists($path = static::getPath($dir, $key))) {
            $disk->delete($path);
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function locked(string $key): bool
    {
        $dir = static::getDir();

        $disk = static::getDisk();

        if ($disk->exists($path = static::getPath($dir, $key))) {
            return $disk->get($path) === '1';
        }

        return false;
    }

    /**
     * @return string
     */
    private static function getDir(): string
    {
        return config('filesystems.dirs.local.exclusive_lock');
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    private static function getDisk()
    {
        return Storage::disk('local');
    }

    /**
     * @param string $dir
     * @param string $key
     *
     * @return string
     */
    private static function getPath(string $dir, string $key): string
    {
        return "{$dir}/{$key}";
    }
}
