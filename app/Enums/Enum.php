<?php

namespace App\Enums;

interface Enum
{
    /**
     * @return array<int, string|int|null>
     */
    public static function values(): array;

    /**
     * @return array<int, string>
     */
    public static function names(): array;
}
