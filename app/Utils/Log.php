<?php

namespace App\Utils;

use App\Exceptions\ErrorUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as BaseLog;
use Monolog\Logger as MonologLogger;

class Log extends BaseLog
{
    /**
     * ログレベル
     */
    public const DEBUG = MonologLogger::DEBUG;
    public const INFO = MonologLogger::INFO;
    public const NOTICE = MonologLogger::NOTICE;
    public const WARNING = MonologLogger::WARNING;
    public const ERROR = MonologLogger::ERROR;
    public const CRITICAL = MonologLogger::CRITICAL;
    public const ALERT = MonologLogger::ALERT;
    public const EMERGENCY = MonologLogger::EMERGENCY;

    /**
     * This is a static variable and not a constant to serve as an extension point for custom levels
     *
     * @var string[] Logging levels with the levels as key
     */
    protected static $levels = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::NOTICE => 'NOTICE',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
        self::CRITICAL => 'CRITICAL',
        self::ALERT => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    public const LOG_EVENT_ERROR = 'Error';

    /**
     * Gets all supported logging levels.
     *
     * @return array assoc array with human-readable level names => level codes
     */
    public static function getLevels(): array
    {
        return array_flip(static::$levels);
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function emergency($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function alert($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function critical($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function error($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function warning($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function notice($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function info($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param string $message
     * @param array $context
     * @param array $options
     *
     * @return void
     */
    public static function debug($message, array $context = [], array $options = [])
    {
        static::log(__FUNCTION__, $message, $context, static::attachDefaultOptions($options));
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private static function attachDefaultOptions(array $options)
    {
        if (($options['logged_from'] ?? false) && !isset($options['logged_from_depth'])) {
            $options['logged_from_depth'] = 3;
        }

        return $options;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @param mixed $options
     *
     * options:
     *  - with_default_context: bool
     *
     * @return void
     */
    public static function log($level, $message, array $context = [], $options = null)
    {
        $withDefaultContext = $options['with_default_context'] ?? false;
        $backtrace = $options['backtrace'] ?? false;

        if (isset($options['logged_from'])) {
            $loggedFrom = $options['logged_from'];
        } else {
            $loggedFrom = isset($options['logged_from_depth']);
        }

        if ($withDefaultContext) {
            $context = array_merge($context, static::getDefaultContext());
        }

        if ($loggedFrom) {
            $loggedFromDepth = $options['logged_from_depth'] ?? 2;
            $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $loggedFromDepth);
            $trace = end($traces);

            $context['logged_from'] = sprintf('%s (%s)', $trace['file'], $trace['line']);
        }

        if ($backtrace) {
            $context['backtrace'] = static::formatBacktrace(debug_backtrace());
        }

        parent::log($level, $message, $context);

        try {
            static::notify($level, $message, $context);
        } catch (\Exception $e) {
            parent::error('Failed skack notification: '.$e->getMessage(), ['exception' => (string) $e]);
        }
    }

    /**
     * バックトレースのフォーマットをする
     *
     * @param array $traces
     *
     * @return string
     */
    public static function formatBacktrace(array $traces)
    {
        return implode("\n", Arr::map($traces, function ($trace, $index): string {
            $method = $trace['function'] ?? '';

            if (isset($trace['class'])) {
                $method = @sprintf('%s%s%s', $trace['class'], $trace['type'], $method);
            }

            if (isset($trace['args'])) {
                $method = @sprintf('%s(%s)', $method, @implode(', ', Arr::map((array) $trace['args'], function ($arg) {
                    if (is_scalar($arg)) {
                        $arg = (string) $arg;

                        return mb_substr($arg, 0, 15) . (mb_strlen($arg) > 15 ? '...' : '');
                    }

                    if (is_object($arg)) {
                        return get_class($arg);
                    }

                    return gettype($arg);
                })));
            }

            return sprintf('#%d %s(%s): %s', $index, $trace['file'] ?? '', $trace['line'] ?? '', $method);
        }));
    }

    /**
     * @param int $level
     *
     * @return bool
     */
    public static function isValidLevel(int $level)
    {
        try {
            MonologLogger::getLevelName($level);

            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @param int $level
     *
     * @return string
     */
    public static function getLevelName(int $level)
    {
        return strtolower(MonologLogger::getLevelName($level));
    }

    /**
     * @param array $context
     * @param string|null $type
     * @param string|null $event
     *
     * @return array
     */
    public static function formatContext(array $context, ?string $type = ErrorUtil::ERROR_TYPE_GENERAL, ?string $event = null)
    {
        return array_filter([
            'event' => $event,
            'type' => $type,
            'params' => $context,
        ]);
    }

    /**
     * @return array
     */
    public static function getDefaultContext()
    {
        return array_merge(static::getUserContext(), [
            'path' => static::getContextPath(),
        ]);
    }

    /**
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private static function getContextPath()
    {
        try {
            if (app()->runningInConsole()) {
                $args = $_SERVER['argv'] ?? [];

                return implode(' ', $args);
            }

            $route = request()->route();

            if ($route instanceof \Illuminate\Routing\Route) {
                return str_replace('@', '::', $route->getActionName());
            }

            return '';
        } catch (\Throwable $e) {
            report($e);

            return '';
        }
    }

    /**
     * @return array
     */
    private static function getUserContext()
    {
        try {
            $user = Auth::user();

            return array_filter([
                'user_id' => optional($user)->id,
            ]);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * @param string $levelName
     *
     * @return int|null
     */
    public static function name2level(string $levelName)
    {
        $levels = static::getLevels();

        return $levels[strtoupper($levelName)] ?? null;
    }

    /**
     * ログレベルによって通知する
     *
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    private static function notify(string $levelName, string $message, array $context)
    {
        // $level = static::name2level($levelName);

        // switch ($level) {
        //     case static::ALERT:
        //         SlackNotifier::sendAlert(['title' => 'ALERT', 'message' => $message, 'fields' => $context]);
        //         break;
        //     case static::EMERGENCY:
        //         SlackNotifier::sendEmergency(['title' => 'EMERGENCY', 'message' => $message, 'fields' => $context]);
        //         break;
        //     default:
        //         break;
        // }
    }
}
