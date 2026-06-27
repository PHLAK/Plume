<?php

declare(strict_types=1);

namespace App\Filters;

class Boolean extends ViewFilter
{
    public string $name = 'boolean';

    public function __invoke(mixed $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_BOOL);
    }
}
