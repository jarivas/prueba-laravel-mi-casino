<?php

namespace App\Enum;

trait Values
{
    public static function values(): array
    {
        $result = [];

        foreach (static::cases() as $case) {
            $result[] = $case->value;
        }

        return $result;
    }
}
