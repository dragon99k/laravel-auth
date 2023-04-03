<?php

namespace App\Utils;

use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File
{
    public const MIME_TYPE_CSV = 'text/csv';
    public const MIME_TYPE_JPG = 'image/jpeg';
    public const MIME_TYPE_PNG = 'image/png';
    public const MIME_TYPE_GIF = 'image/gif';
    public const MIME_TYPE_WEBP = 'image/webp';
    public const MIME_TYPE_PDF = 'application/pdf';

    public const EXT_CSV = 'csv';
    public const EXT_JPG = 'jpg';
    public const EXT_PNG = 'png';
    public const EXT_GIF = 'gif';
    public const EXT_WEBP = 'webp';
    public const EXT_PDF = 'pdf';

    public static function getIgnoreZipContentNames()
    {
        return [
            '__MACOSX',
        ];
    }

    /**
     * @param string $disk
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function getDisk(string $disk)
    {
        return Storage::disk($disk);
    }

    /**
     * @param string|null $disk
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function getPublicImageDisk(?string $disk = null)
    {
        return static::getDisk($disk ?? config('filesystems.image'));
    }

    /**
     * 公開用画像をドライブに置く
     *
     * @param string $filePath
     * @param string $content
     *
     * @return string 新しい画像URL
     */
    public static function putPublicImage($filePath, $content)
    {
        $disk = static::getPublicImageDisk();

        $disk->put($filePath, $content);

        return $disk->url($filePath);
    }

    /**
     * 公開用画像をドライブから削除する
     *
     * @param string $filePath
     *
     * @return void
     */
    public static function deletePublicImage($filePath)
    {
        $filePath = static::nomarizePublicImagePath($filePath);

        $disk = static::getPublicImageDisk();

        $disk->delete($filePath);
    }

    /**
     * 公開用画像をコピーする
     *
     * @param string $filePath
     *
     * @return void
     */
    public static function copyPublicImage(string $srcPath, string $destPath)
    {
        $srcPath = static::nomarizePublicImagePath($srcPath);
        $destPath = static::nomarizePublicImagePath($destPath);

        $disk = static::getPublicImageDisk();

        if ($disk->exists($destPath)) {
            $disk->delete($destPath);
        }

        $disk->copy($srcPath, $destPath);
    }

    /**
     * @param \Illuminate\Contracts\Filesystem\Filesystem $disk
     * @param string $path
     *
     * @return string
     */
    private static function nomarizePublicImagePath(string $path)
    {
        $disk = static::getPublicImageDisk();

        $baseUrl = rtrim($disk->url(''), '/');

        if (strpos($path, $baseUrl) === 0) {
            $path = str_replace($baseUrl, '', $path);
        }

        if (strpos($path, 'http') === 0) {
            $path = \App\Utils\Url::extractPath($path);
        }

        return $path;
    }

    /**
     * @param string|null $disk
     *
     * @return string
     */
    public static function generateTempDir(?string $disk = 'local')
    {
        $disk = static::getDisk($disk);

        $uuid = Str::uuid();

        return sprintf('%s/%s', config('filesystems.dirs.local.tmp'), $uuid);
    }

    /**
     * 任意のパスがディレクトリか判定する。
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isDirectory(string $path)
    {
        return is_dir($path);
    }

    /**
     * 任意のパスにディレクトリを作成する。
     *
     * @param string $directory
     * @param int $permissions
     * @param bool $recursive
     *
     * @return bool
     */
    public static function makeDirectory(string $directory, int $permissions = 0755, $recursive = true)
    {
        return mkdir($directory, $permissions, $recursive);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public static function isBase64Encoded($data)
    {
        return (bool) preg_match('/data:(.+);base64/', (string) $data);
    }

    /**
     * Base64からデータをContentTypeとデコード済みの値を抽出する
     *
     * @param string $data
     *
     * @return array
     */
    public static function extractContentBase64(string $data)
    {
        list($header, $content) = explode(',', $data);

        if (!preg_match('/data:(.+);base64/', $header, $matchs)) {
            throw new InvalidArgumentException(__('error.invalid_upload_file'));
        }

        $contentType = $matchs[1];

        return [base64_decode($content), $contentType];
    }

    /**
     * @param string $contentType
     * @param array $acceptable
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function validateContentType(string $contentType, array $acceptable = ['*'])
    {
        if (current($acceptable) !== '*' && !in_array(strtolower($contentType), $acceptable)) {
            throw new InvalidArgumentException(__('error.invalid_content_type'));
        }

        return true;
    }

    /**
     * @param array $file
     * @param string $directory
     * @param array $options
     *
     * @return string
     */
    public static function storeBase64(array $file, string $directory, array $options = []): string
    {
        if (!static::isBase64Encoded($file['data'])) {
            throw new InvalidArgumentException(__('error.invalid_upload_file'));
        }

        [$copntent] = static::extractContentBase64($file['data']);

        $path = sprintf('%s/%s', $directory, $file['filename']);

        Storage::put($path, $copntent, $options);

        return $path;
    }
}
