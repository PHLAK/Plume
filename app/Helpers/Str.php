<?php

declare(strict_types=1);

namespace App\Helpers;

class Str
{
    /** @return array<mixed, string|null> */
    public static function extract(string $pattern, string $subject): array
    {
        preg_match($pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL);

        return array_filter(array_slice($matches, 1), fn ($key): bool => is_integer($key), ARRAY_FILTER_USE_KEY);
    }
}
