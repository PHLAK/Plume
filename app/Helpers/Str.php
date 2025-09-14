<?php

declare(strict_types=1);

namespace App\Helpers;

class Str
{
    /** @return array<mixed, string|null> */
    public static function match(string $pattern, string $subject, bool $full = false): array
    {
        preg_match($pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL);

        return $full ? $matches : array_slice($matches, 1);
    }
}
