<?php

namespace App\Exceptions;

use App\Exceptions\Contracts\ReportableException;
use App\Utils\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;

class ErrorUtil
{
    public const ERROR_TYPE_GENERAL = 'General';
    public const ERROR_TYPE_ERROR = 'Error';

    /**
     * @param \Throwable $exception
     * @param string|null $message
     * @param string $class
     *
     * @return ReportableException
     */
    public static function wrap(\Throwable $exception, ?string $message = null)
    {
        if (($exception instanceof ReportableException) === false) {
            $exception = new WrapperException($message ?? $exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return $exception;
    }

    /**
     * @param \Throwable $exception
     * @param string|null $messge
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function report(\Throwable $exception, ?string $messge = null, array $context = [], array $options = [])
    {
        $loggedFrom = $options['logged_from'] ?? true;
        $loggedFromDepth = $options['logged_from_depth'] ?? 2;

        $wrappedException = static::wrap($exception);
        $level = Log::getLevelName($options['level'] ?? $wrappedException->getLogLevel());
        $type = $options['type'] ?? $wrappedException->getContextType();
        $event = $options['event'] ?? Log::LOG_EVENT_ERROR;

        $message = $messge ?? $exception->getMessage();
        $context = array_merge(static::formatContext($exception, $type, $event), $context);

        Log::log($level, $message, $context, [
            'with_default_context' => true,
            'logged_from_depth' => $loggedFromDepth,
            'logged_from' => $loggedFrom,
        ]);
    }

    /**
     * @param \Throwable $exception
     * @param string|null $messge
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function alert(\Throwable $exception, ?string $messge = null, array $context = [], array $options = [])
    {
        $messge = sprintf('<!channel> %s', $messge ?? $exception->getMessage());

        static::report($exception, $messge, $context, array_merge($options, [
            'level' => Log::ALERT,
            'logged_from_depth' => $options['logged_from_depth'] ?? 3,
        ]));
    }

    /**
     * @param \Throwable $exception
     * @param string|null $messge
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function emergency(\Throwable $exception, ?string $messge = null, array $context = [], array $options = [])
    {
        $messge = sprintf('<!channel> %s', $messge ?? $exception->getMessage());

        static::report($exception, $messge, $context, array_merge($options, [
            'level' => Log::EMERGENCY,
            'logged_from_depth' => $options['logged_from_depth'] ?? 3,
        ]));
    }

    /**
     * ログコンテキストをフォーマットする
     *
     * @param \Throwable $exception
     * @param string|null $type
     * @param string|null $event
     *
     * @return array
     */
    public static function formatContext(\Throwable $exception, ?string $type = null, ?string $event = Log::LOG_EVENT_ERROR)
    {
        if (is_null($type)) {
            if ($exception instanceof \Error) {
                $type = static::ERROR_TYPE_ERROR;
            } else {
                $type = static::ERROR_TYPE_GENERAL;
            }
        }

        $context = static::extractContext($exception);

        return array_merge(
            Log::formatContext($context, $type, $event),
            ['exception' => (string) $exception]
        );
    }

    /**
     * contextを取得する
     *
     * @param \Throwable $exception
     *
     * @return array
     */
    public static function extractContext(\Throwable $exception)
    {
        if ($exception instanceof ReportableException) {
            return $exception->getContext();
        }

        if ($exception instanceof EloquentModelNotFoundException) {
            return array_filter([
                'model' => $exception->getModel(),
                'ids' => $exception->getIds(),
            ]);
        }

        return [];
    }
}
