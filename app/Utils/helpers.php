<?php

use App\Utils\Arr;
use Illuminate\Support\Facades\Route;

if (!function_exists('json')) {
    function json($value, bool $camelize = false)
    {
        if ($camelize && is_array($value)) {
            $value = Arr::camerizeKeys($value);
        }

        return json_encode($value, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS);
    }
}

if (!function_exists('msleep')) {
    function msleep(int $ms)
    {
        return usleep($ms * 1000);
    }
}

if (!function_exists('mb_memory_get_peak_usage')) {
    function mb_memory_get_peak_usage(bool $realUsage = false)
    {
        return memory_get_peak_usage($realUsage) / 1024 / 1024;
    }
}

if (!function_exists('mb_memory_get_usage')) {
    function mb_memory_get_usage(bool $realUsage = false)
    {
        return memory_get_usage($realUsage) / 1024 / 1024;
    }
}

if (!function_exists('echo_boolean')) {
    function echo_boolean(bool $condition)
    {
        return $condition ? 'true' : 'false';
    }
}

if (!function_exists('uri')) {
    /**
     * @param string $name
     *
     * @return string
     */
    function uri_template(string $name): string
    {
        return Route::getRoutes()->getByName($name)->uri();
    }
}

if (!function_exists('resolve_route')) {
    function resolve_route(string $name, array $params = [])
    {
        try {
            return route($name, $params);
        } catch (\Exception $e) {
            return uri_template($name);
        }
    }
}

if (!function_exists('routes')) {
    /**
     * @param array|string $names
     *
     * @return array
     */
    function routes(array|string $names): array
    {
        $urls = [];

        foreach ($names as $key => $route) {
            if (is_string($route)) {
                $urls[$route] = resolve_route($route);
            } else {
                if (count($route) === 1) {
                    $route[] = [];
                }

                [$name, $params] = $route;
                $urls[is_numeric($key) ? $name : $key] = resolve_route($name, $params);
            }
        }

        return $urls;
    }
}

if (!function_exists('json_routes')) {
    /**
     * @param array|string $names
     *
     * @return string
     */
    function json_routes(array|string $names): string
    {
        return json(routes($names));
    }
}
