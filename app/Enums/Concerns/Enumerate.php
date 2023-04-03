<?php

namespace App\Enums\Concerns;

trait Enumerate
{
    /**
     * @return array<int, string|int|null>
     */
    public static function values(): array
    {
        return array_map(fn (\UnitEnum $enum) => $enum->value ?? null, self::cases());
    }

    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_map(fn (\UnitEnum $enum) => $enum->name, self::cases());
    }
}
