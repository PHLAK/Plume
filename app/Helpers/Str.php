<?php

declare(strict_types=1);

namespace App\Helpers;

class Str
{
    /** @return array<mixed, string|null> */
    public static function match(string $pattern, string $subject): array
    {
        preg_match($pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL);

        return array_filter($matches, fn ($key): bool => is_string($key), ARRAY_FILTER_USE_KEY);
    }
}
